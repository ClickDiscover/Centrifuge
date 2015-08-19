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

        // $app->container->singleton('debugBar', function() {
        //     return new \DebugBar\StandardDebugBar();
        // });
        // $debug->addCollector($container['logger']);
        $app->add($container['debug.bar']);
        // $app->view->appendData(['debug' => $app->container['debugBar']->getJavascriptRenderer()]);

        return $app;
    }

    public function setupLogging(Slim $app, Container $c) {
        $app->log->setWriter($c['logger']);
        // $app->container->singleton('log', function () use ($c) {
        //     return $c['logger'];
        // });
    }
}
