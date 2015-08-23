<?php

namespace Flagship;

use Flagship\Container;
use Slim\Slim;
use \Stash\Session as StashSession;

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
            $url = $app->urlFor($route, $params);
            $query = http_build_query($app->request->get());
            return $url . "?" . $query;
        });

        $app->log->setWriter($container['logger']);

        $app->view($container['plates']);
        $app->container->singleton('db', function() use ($container) {
            return $container['db'];
        });

        $app->container['custom.routes'] = function () use ($container) {
            return $container['custom.routes']->fetchAll();
        };

        $this->setupHooks();
        $app->add(new \Flagship\Middleware\Session($container['session.cache']));
        $app->add($container['debug.bar']);

        // $app->add(new \Flagship\Middleware\LanderFallback($container['config']['application']['fallback_lander']));

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
