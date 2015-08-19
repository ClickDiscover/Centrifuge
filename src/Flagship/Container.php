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
            return $debug;
        };

        // Logging
        $this['logger.path'] = $c['config']['logging']['root'] . '/' . $c['config']['name'] . '.log';

        $this['logger'] = $this->factory(function () use ($c) {
            $log = new Logger($c['config']['name']);
            $log->pushHandler(new StreamHandler(
                $c['logger.path'],
                Logger::toMonologLevel($c['config']['logging']['level'])
            ));
            $log->pushProcessor(new \Monolog\Processor\WebProcessor);
            $log->pushProcessor(new \Monolog\Processor\MemoryUsageProcessor);
            return $log;
        });

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

            $plates = new Engine($templateRoot);
            $plates->loadExtension(new VariantExtension);
            $plates->loadExtension(new HtmlExtension);
            $view = new ViewEngine($plates);
            $view->addFolder('admin', $c['config']['application']['templates.path'] . '/admin');
            return $view;
        };

        $this['db'] = function () use ($c) {
            return new QueryCache(
                $c['pdo'],
                $c['cache'],
                $c['config']['cache']['expiration']
            );
        };

        $this['offer.network'] = function () use ($c) {
            return new NetworkOfferService($c['db'], $c['config']['cache']['expiration']);
        };

        $this['offer.adex'] = function () use ($c) {
            return new AdexOfferService($c['db'], $c['cache'], $c['config']['cache']['adex.expiration']);
        };

        $this['offers'] = function () use ($c) {
            return new \Flagship\Service\OfferService($c['offer.network'], $c['offer.adex']);
        };

        $this['landers'] = function () use ($c) {
            return new \Flagship\Service\LanderService($c['db'], $c['offers']);
        };
    }
}
