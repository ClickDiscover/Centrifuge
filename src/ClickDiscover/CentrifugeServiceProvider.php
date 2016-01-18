<?php

namespace ClickDiscover;

// use Slim\App;
// use Slim\Container;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class CentrifugeServiceProvider implements \Pimple\ServiceProviderInterface {

    protected $settings;

    public function register(\Pimple\Container $container) {

        $container['pdo'] = function ($c) {
            $pdo = new \F3\LazyPDO\LazyPDO($c['settings']['database']['pdo'], null, null, array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ));
            return $pdo;
        };

    }

    public function dontuse(\Pimple\Container $container) {

        // View
        $this['plates'] = function ($c) {
            $templateRoot = $c['config']['application']['templates.path'] . $c['config']['paths']['relative_landers'];
            $assetRoot = $c['config']['paths']['relative_static'];

            $plates = new Engine($templateRoot);
            $plates->loadExtension(new VariantExtension);
            $plates->loadExtension(new HtmlExtension);
            $view = new ViewEngine($plates, $assetRoot);
            $view->addFolder('admin', $c['config']['application']['templates.path'] . '/admin');
            $view->setProfiler($c['profiler']);
            return $view;
        };

        // Centrifuge Services
        $this['custom.routes'] = function ($c) {
            return new CustomRouteService($c['db']);
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
            $landers = new \Flagship\Service\LanderService($c['db'], $c['offers']);
            $landers->setProfiler($c['profiler']);
            return $landers;
        };

        // Database Handlers / Logging / Utility
        $container['logger'] = function ($c) {
            $log = new \Monolog\Logger($c['settings']['name']);
            $log->pushHandler(new \Monolog\Handler\StreamHandler(
                $c['settings']['logging']['path'],
                \Monolog\Logger::DEBUG
            ));
            return $log;
        };

        // $container['pdo'] = function ($c) {
            // $pdo = new \F3\LazyPDO\LazyPDO($c['settings']['database']['pdo'], null, null, array(
                // \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            // ));
            // return $pdo;
        // };

        // Cache
        $this['cacheDriver'] = function ($c) {
            $driver = new \Stash\Driver\FileSystem();
            $driver->setOptions(array('path' => $c['settings']['cache']['root']));
            return $driver;
        };
        $this['cache'] = function ($c) {
            $cache = new Pool($c['cacheDriver']);
            $cache->setNamespace($c['settings']['name']);
            return $cache;
        };

        $this['db'] = function ($c) {
            $db = new QueryCache(
                $c['pdo'],
                $c['cache'],
                $c['config']['cache']['expiration']
            );
            $db->setLogger($c['logger']);
            // $db->setProfiler($c['profiler']);
            return $db;
        };

        $this['fs'] = function ($c) {
            $adapter = new \League\Flysystem\Adapter\Local($c['config']['application']['templates.path']);
            $fs = new \League\Flysystem\Filesystem($adapter);
            $fs->addPlugin(new \League\Flysystem\Plugin\ListWith);
            return $fs;
        };


        // Session
        $this['session.cache'] = function ($c) {
            $sessionCache= new Pool($c['cacheDriver']);
            $sessionCache->setNamespace('session');
            return $sessionCache;
        };

        // Cookies
        $this['hashids'] = function ($c) {
            return new \Hashids\Hashids(
                $c['settings']['hashids']['salt'],
                $c['settings']['hashids']['length']
            );
        };

        $this['cookie.jar'] = function ($c) {
            return new CookieJar(
                $c['hashids'],
                $c['settings']['cookie']['root.domain'],
                $c['settings']['cookie']['session.lifetime'],
                $c['settings']['cookie']['visitor.lifetime']
            );
        };

        $this['random.id'] = $this->factory(function ($c) {
            return $c['cookie.jar']->getRandomId();
        });

        $this['context.factory'] = function ($c) {
            return new EventContextFactory($c['config']['application']['tracking']);
        };


    }
}
