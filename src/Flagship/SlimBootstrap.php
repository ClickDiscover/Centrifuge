<?php

namespace Flagship;

use Flagship\Container;
use Slim\Slim;

class SlimBootstrap {

    protected $app;
    protected $container;

    public function __construct(Slim $app, Container $container) {
        $this->app = $app;
        $this->container = $container;
    }

    public function bootstrap() {
        $app = $this->app;
        $container = $this->container;

        $container['offers']->setUrlFor(function ($route, $params) use ($app) {
            return $app->urlFor($route, $params);
        });


        // $app->log->setWriter($container['logger']);
        $app->log->setWriter($container['logger']);

        $app->view($container['plates']);
        $app->container->singleton('db', function() use ($container) {
            return $container['db'];
        });

        $app->container['custom.routes'] = function () use ($container) {
            return $container['custom.routes']->fetchAll();
        };

        $this->setupHooks();
        $app->add($container['debug.bar']);

        return $app;
    }

    public function setupHooks() {
        $app = $this->app;
        $app->hook("slim.before", function () use ($app) {
            $uri = $app->environment['PATH_INFO'];
            $custom = $app->container['custom.routes'];
            foreach ($custom as $c) {
                if ($c['url'] === $uri) {
                    $newUrl = $app->urlFor('landers', array('id' => $c['lander_id']));
                    $app->environment['PATH_INFO'] = $newUrl;
                }
            }
        });
    }
}
