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

        $this->setupHooks();
        $this->configureDevelopmentMode();
        $this->configureProductionMode();

        $container['offers']->setUrlFor(function ($route, $params) use ($app) {
            $url = $app->urlFor($route, $params);
            $get = $app->request->get();
            if (count($get) == 0) {
                return $url;
            } else {
                $query = http_build_query($get);
                return $url . "?" . $query;
            }
        });

        $app->log->setWriter($container['logger']);

        $app->view($container['plates']);
        $app->container->singleton('db', function() use ($container) {
            return $container['db'];
        });

        $app->container['custom.routes'] = function () use ($container) {
            return $container['custom.routes']->fetchAll();
        };

        $app->add(new \Flagship\Middleware\UserTracker(
            new \Hashids\Hashids(
                $container['config']['hashids']['salt'],
                $container['config']['hashids']['length']
            )
        ));

        $app->add(new \Flagship\Middleware\Session($container['session.cache']));

        return $app;
    }

    public function configureDevelopmentMode() {
        $app = $this->app;
        $container = $this->container;
        $app->configureMode('development', function () use ($app, $container) {
            $app->add($container['debug.bar']);
        });
    }

    public function configureProductionMode() {
        $app = $this->app;
        $container = $this->container;
        $app->configureMode('production', function () use ($app, $container) {
            // Fallback to lander
            $app->notFound(function () use ($app, $container) {
                $fallback = $app->config('fallback_lander');

                $container['logger']->warning('4xx', [$app->request->getPathInfo()]);
                $container['librato.system']->total('4xx');

                if (isset($fallback)) {
                    $app->redirect($app->urlFor('landers', array('id' => $fallback)));
                }
            });
        });
    }

    public function setupHooks() {
        $app = $this->app;
        $container = $this->container;

        // Statsd Reporting
        $timerMetricName = $container['librato.system']->totalName('request_time');
        $app->hook("slim.before", function () use ($container, $timerMetricName) {
            $container['librato.system']->total('num_requests');
            $container['statsd']->startTiming($timerMetricName);
        });

        $app->hook("slim.after", function () use ($container, $timerMetricName) {
            $container['statsd']->endTiming($timerMetricName);
        });

        // Custom URL handler
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
