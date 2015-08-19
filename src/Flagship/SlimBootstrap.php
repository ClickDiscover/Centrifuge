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
        $app->log->setWriter($container['logger']);

        $app->view($container['plates']);
        $app->container->singleton('db', function() use ($container) {
            return $container['db'];
        });
        $app->container->singleton('centrifuge', function() use ($container) {
            return $container['config'];
        });

        $app->add($container['debug.bar']);

        $container['offers']->setUrlFor(function ($route, $params) use ($app) {
            return $app->urlFor($route, $params);
        });

        return $app;
    }
}
