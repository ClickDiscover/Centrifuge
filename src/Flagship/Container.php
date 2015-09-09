<?php

namespace Flagship;

use Stash\Driver\FileSystem;
use Stash\Pool;
use \Domnikl\Statsd\Connection\UdpSocket as StatsdSocket;
use \Domnikl\Statsd\Client as Statsd;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use League\Plates\Engine;
use Slim\Middleware\DebugBar;

use Flagship\Util\Logger;
use Flagship\Plates\VariantExtension;
use Flagship\Plates\HtmlExtension;
use Flagship\Plates\ViewEngine;
use Flagship\Storage\QueryCache;
use Flagship\Service\NetworkOfferService;
use Flagship\Service\AdexOfferService;
use Flagship\Service\CustomRouteService;
use Flagship\Storage\LibratoStorage;
use Flagship\Storage\CookieJar;
use Flagship\Storage\SegmentStorage;
use \Flagship\Event\EventContextFactory;


class Container extends \Pimple\Container {

    public function __construct(array $config) {
        parent::__construct();
        $this['config'] = $config;
        $this->configure();
    }


    public function configure() {

        // Database
        $this['pdo'] = function ($c) {
            $pdo = new \F3\LazyPDO\LazyPDO($c['config']['database']['pdo'], null, null, array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ));
            return $pdo;
        };

        $this['debug.bar'] = function ($c) {
            $debug = new DebugBar();
            $debug->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector(
                new \DebugBar\DataCollector\PDO\TraceablePDO($c['pdo'])
            ));
            $debug->addCollector(new \DebugBar\Bridge\MonologCollector($c['logger']));
            return $debug;
        };

        // Logging
        $this['logger.path'] = $this['config']['logging']['root'] . '/' . $this['config']['name'] . '.log';

        $this['logger'] = function ($c) {
            $log = new Logger($c['config']['name']);
            $log->pushHandler(new StreamHandler(
                $c['logger.path'],
                Logger::toMonologLevel($c['config']['logging']['level'])
            ));
            Logger::setInstance($log);
            return $log;
        };

        // Cache
        $this['cacheDriver'] = function ($c) {
            $driver = new FileSystem();
            $driver->setOptions(array('path' => $c['config']['cache']['root']));
            return $driver;
        };
        $this['cache'] = function ($c) {
            $cache = new Pool($c['cacheDriver']);
            $cache->setNamespace($c['config']['name']);
            return $cache;
        };

        // Session
        $this['session.cache'] = function ($c) {
            $sessionCache= new Pool($c['cacheDriver']);
            $sessionCache->setNamespace('session');
            return $sessionCache;
        };

        $this['hashids'] = function ($c) {
            return new \Hashids\Hashids(
                $c['config']['hashids']['salt'],
                $c['config']['hashids']['length']
            );
        };

        // Cookies
        $this['cookie.jar'] = function ($c) {
            return new CookieJar(
                $c['hashids'],
                $c['config']['cookie']['root.domain'],
                $c['config']['cookie']['session.lifetime'],
                $c['config']['cookie']['visitor.lifetime']
            );
        };

        $this['random.id'] = $this->factory(function ($c) {
            return $c['cookie.jar']->getRandomId();
        });

        $this['context.factory'] = function ($c) {
            return new EventContextFactory($c['config']['application']['tracking']);
        };

        // Plates
        $this['plates'] = function ($c) {
            $templateRoot = $c['config']['application']['templates.path'] . $c['config']['paths']['relative_landers'];
            $assetRoot = $c['config']['paths']['relative_static'];

            $plates = new Engine($templateRoot);
            $plates->loadExtension(new VariantExtension);
            $plates->loadExtension(new HtmlExtension);
            $view = new ViewEngine($plates, $assetRoot);
            $view->addFolder('admin', $c['config']['application']['templates.path'] . '/admin');
            return $view;
        };

        $this['custom.routes'] = function ($c) {
            return new CustomRouteService($c['db']);
        };


        $this['db'] = function ($c) {
            $db = new QueryCache(
                $c['pdo'],
                $c['cache'],
                $c['config']['cache']['expiration']
            );
            $db->setLogger($c['logger']);
            return $db;
        };

        $this['fs'] = function ($c) {
            $adapter = new \League\Flysystem\Adapter\Local($c['config']['application']['templates.path']);
            $fs = new \League\Flysystem\Filesystem($adapter);
            $fs->addPlugin(new \League\Flysystem\Plugin\ListWith);
            return $fs;
        };

        $this['offer.network'] = function ($c) {
            return new NetworkOfferService($c['db'], $c['config']['application']['product.path']);
        };

        $this['offer.adex'] = function ($c) {
            $service = new AdexOfferService($c['db'], $c['cache'], $c['config']['cache']['adex.expiration']);
            $service->setLogger($c['logger']);
            return $service;
        };

        $this['offers'] = function ($c) {
            return new \Flagship\Service\OfferService($c['offer.network'], $c['offer.adex']);
        };

        $this['landers'] = function ($c) {
            return new \Flagship\Service\LanderService($c['db'], $c['offers']);
        };

        // Conversions
        $this['redis'] = function ($c) {
            return new \Predis\Client($c['config']['database']['redis']);
        };

        $this['conversions'] = function ($c) {
            return new \Flagship\Service\ConversionService($c['redis']);
        };

        // Librato
        $this['statsd'] = function ($c) {
            $conn = new StatsdSocket('localhost', 8125);
            return new Statsd($conn);
        };

        $this['librato.performance'] = function ($c) {
            $librato = new LibratoStorage(
                $c['statsd'],
                [$c['config']['environment']],
                [$c['config']['name'], 'performance']
            );
            $librato->setLogger($c['logger']);
            return $librato;
        };

        $this['librato.system'] = function ($c) {
            $librato = new LibratoStorage(
                $c['statsd'],
                [$c['config']['environment'], $c['config']['hostname']],
                [$c['config']['name'], 'system']
            );
            $librato->setLogger($c['logger']);
            return $librato;
        };

        $this['segment'] = function ($c) {
            return new SegmentStorage(
                $c['config'],
                $c['cookie.jar'],
                $c['logger']
            );
        };

        $this['aerospike'] = function ($c) {
            $conf = $c['config']['database']['aerospike'];
            $db = new \Aerospike($conf['client']);
            // $db->setLogLevel($conf['log_level']);
            // $db->setLogHandler(function ($level, $file, $function, $line) use ($c) {
            //     var_dump([$level, $file, $function, $line]);
            //     $c['logger']->info('Aerospike: ', [$level, $file, $function, $line]);
            // });
            return $db;
        };
    }
}


class DebugContainer extends Container {
    public function __construct(array $config) {
        parent::__construct();
        $this->clog = new \Monolog\Logger('container');
        $this->clog->pushHandler(new StreamHandler(
            '/tmp/container.log', \Monolog\Logger::INFO
        ));
    }
    public function offsetGet($id) {
        $config = [$id];
        if(isset($_SERVER['REQUEST_URI'])) {
            $config[] = $_SERVER['REQUEST_URI'];
        }
        $this->clog->info("Id", $config);
        return parent::offsetGet($id);
    }
}


