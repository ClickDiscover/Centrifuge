<?php

namespace Flagship;

use Stash\Driver\FileSystem;
use Stash\Pool;
use Stash\Session as StashSession;
use \Domnikl\Statsd\Connection\UdpSocket as StatsdSocket;
use \Domnikl\Statsd\Client as Statsd;
// use Monolog\Logger;
use Flagship\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use League\Plates\Engine;
use Slim\Middleware\DebugBar;

use Flagship\Plates\VariantExtension;
use Flagship\Plates\HtmlExtension;
use Flagship\Plates\ViewEngine;
use Flagship\Storage\QueryCache;
use Flagship\Service\NetworkOfferService;
use Flagship\Service\AdexOfferService;
use Flagship\Service\CustomRouteService;


class Container extends \Pimple\Container {

    public function __construct(array $config) {
        parent::__construct();
        $this['config'] = $config;
        $this->configure();
    }


    public function configure() {
        $c = $this;

        // Database
        $this['pdo'] = function () use ($c) {
            return new \PDO($c['config']['database']['pdo'], null, null, array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ));
        };

        $this['debug.bar'] = function () use ($c) {
            $debug = new DebugBar();
            $debug->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector(
                new \DebugBar\DataCollector\PDO\TraceablePDO($c['pdo'])
            ));
            $debug->addCollector(new \DebugBar\Bridge\MonologCollector($c['logger']));
            return $debug;
        };

        // Logging
        $this['logger.path'] = $c['config']['logging']['root'] . '/' . $c['config']['name'] . '.log';

        $this['logger'] = function () use ($c) {
            $log = new Logger($c['config']['name']);
            $log->pushHandler(new StreamHandler(
                $c['logger.path'],
                Logger::toMonologLevel($c['config']['logging']['level'])
            ));
            return $log;
        };

        // Cache
        $this['cacheDriver'] = function () use ($c) {
            $driver = new FileSystem();
            $driver->setOptions(array('path' => $c['config']['cache']['root']));
            return $driver;
        };
        $this['cache'] = function () use ($c) {
            $cache = new Pool($this['cacheDriver']);
            $cache->setNamespace($c['config']['name']);
            return $cache;
        };
        // Session
        $this['sessionCache'] = function () use ($c) {
            $sessionCache= new Pool($this['cacheDriver']);
            $sessionCache->setNamespace('session');
            // StashSession::registerHandler(new StashSession($sessionCache));
            return $sessionCache;
        };

        // Statsd
        $this['statsd'] = function () use ($c) {
            $conn = new StatsdSocket('localhost', 8125);
            return new Statsd($conn);
        };

        // Plates
        $this['plates'] = function () use ($c) {
            $templateRoot = $c['config']['application']['templates.path'] . $c['config']['paths']['relative_landers'];
            $assetRoot = $c['config']['paths']['relative_static'];

            $plates = new Engine($templateRoot);
            $plates->loadExtension(new VariantExtension);
            $plates->loadExtension(new HtmlExtension);
            $view = new ViewEngine($plates, $assetRoot);
            $view->addFolder('admin', $c['config']['application']['templates.path'] . '/admin');
            return $view;
        };

        $this['custom.routes'] = function () use ($c) {
            return new CustomRouteService($c['db']);
        };


        $this['db'] = function () use ($c) {
            $db = new QueryCache(
                $c['pdo'],
                $c['cache'],
                $c['config']['cache']['expiration']
            );
            $db->setLogger($c['logger']);
            return $db;
        };

        $this['fs'] = function () use ($c) {
            $adapter = new \League\Flysystem\Adapter\Local($c['config']['application']['templates.path']);
            $fs = new \League\Flysystem\Filesystem($adapter);
            $fs->addPlugin(new \League\Flysystem\Plugin\ListWith);
            return $fs;
        };

        $this['offer.network'] = function () use ($c) {
            return new NetworkOfferService($c['db'], $c['config']['application']['product.path']);
        };

        $this['offer.adex'] = function () use ($c) {
            $service = new AdexOfferService($c['db'], $c['cache'], $c['config']['cache']['adex.expiration']);
            $service->setLogger($c['logger']);
            return $service;
        };

        $this['offers'] = function () use ($c) {
            return new \Flagship\Service\OfferService($c['offer.network'], $c['offer.adex']);
        };

        $this['landers'] = function () use ($c) {
            return new \Flagship\Service\LanderService($c['db'], $c['offers']);
        };
    }
}
