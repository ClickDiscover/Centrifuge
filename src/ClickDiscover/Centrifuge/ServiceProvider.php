<?php

namespace ClickDiscover\Centrifuge;

// use Slim\App;
// use Slim\Container;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use ClickDiscover\View\ViewEngine;


class ServiceProvider implements \Pimple\ServiceProviderInterface {

    protected $settings;

    public function register(\Pimple\Container $container) {
        $container['pdo'] = function ($c) {
            $pdo = new \F3\LazyPDO\LazyPDO($c['settings']['database']['pdo'], null, null, array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ));
            return $pdo;
        };

        $container['offer.network'] = function ($c) {
            return new \Flagship\Service\NetworkOfferService($c['db'], $c['settings']['application']['product.path']);
        };

        $container['offer.adex'] = function ($c) {
            $service = new \Flagship\Service\AdexOfferService($c['db'], $c['cache'], $c['settings']['cache']['adex.expiration']);
            $service->setLogger($c['logger']);
            return $service;
        };

        $container['offers'] = function ($c) {
            $offers = new \Flagship\Service\OfferService($c['offer.network'], $c['offer.adex']);
            $offers->setUrlFor(function ($name, $args) use ($c) {
                return '/click' . '/' . $args['stepId'];
            });
            return $offers;
        };

        $container['landers'] = function ($c) {
            $landers = new \Flagship\Service\LanderService($c['db'], $c['offers']);
            return $landers;
        };

        $container['logger'] = function ($c) {
            $log = new \Monolog\Logger($c['settings']['name']);
            $log->pushHandler(new \Monolog\Handler\StreamHandler(
                $c['settings']['logging']['root'],
                $c['settings']['logging']['level']
            ));
            return $log;
        };


        // Cache
        $container['cacheDriver'] = function ($c) {
            $driver = new \Stash\Driver\FileSystem();
            $driver->setOptions(array('path' => $c['settings']['cache']['root']));
            return $driver;
        };
        $container['cache'] = function ($c) {
            $cache = new \Stash\Pool($c['cacheDriver']);
            $cache->setNamespace($c['settings']['name']);
            return $cache;
        };

        $container['session.cache'] = function ($c) {
            $sessionCache = new \Stash\Pool($c['cacheDriver']);
            $sessionCache->setNamespace('session');
            return $sessionCache;
        };


        $container['db'] = function ($c) {
            $db = new \Flagship\Storage\QueryCache(
                $c['pdo'],
                $c['cache'],
                $c['settings']['cache']['expiration']
            );
            $db->setLogger($c['logger']);
            // $db->setProfiler($c['profiler']);
            return $db;
        };

        $container['fs'] = function ($c) {
            $adapter = new \League\Flysystem\Adapter\Local($c['settings']['application']['templates.path']);
            $fs = new \League\Flysystem\Filesystem($adapter);
            $fs->addPlugin(new \League\Flysystem\Plugin\ListWith);
            return $fs;
        };

        $container['settings']['templateRoot'] = $container['settings']['paths']['templates.path'] . $container['settings']['paths']['relative.landers'];
        $container['settings']['assetRoot'] = $container['settings']['paths']['relative.static'];

        // View
        $container['plates'] = function ($c) {
            $templateRoot = $c['settings']['templateRoot'];
            $assetRoot = $c['settings']['assetRoot'];

            $plates = new \League\Plates\Engine($templateRoot);
            $plates->loadExtension(new \Flagship\Plates\VariantExtension);
            $plates->loadExtension(new \Flagship\Plates\HtmlExtension);
            $view = new \ClickDiscover\View\PlatesEngine($plates, $assetRoot);
            $view->addFolder('admin', $c['settings']['paths']['templates.path'] . '/admin');
            return $view;
        };



    }

    public function dontuse(\Pimple\Container $container) {

        // Centrifuge Services
        $this['custom.routes'] = function ($c) {
            return new CustomRouteService($c['db']);
        };
        // Database Handlers / Logging / Utility
        // $container['pdo'] = function ($c) {
            // $pdo = new \F3\LazyPDO\LazyPDO($c['settings']['database']['pdo'], null, null, array(
                // \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            // ));
            // return $pdo;
        // };

        // Session
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
