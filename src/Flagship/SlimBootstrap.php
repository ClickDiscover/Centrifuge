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
        $this->setupLogging($app, $container);

        $app->view($container['plates']);
        $app->container->singleton('db', function() use ($container) {
            return $container['db'];
        });
        $app->container->singleton('centrifuge', function() use ($container) {
            return $container['config'];
        });

        return $app;
    }

    public function setupLogging(Slim $app, Container $c) {
        $app->container->singleton('log', function () use ($c) {
            return $c['logger'];
        });
    }
}
