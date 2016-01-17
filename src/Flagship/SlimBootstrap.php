<?php

namespace Flagship;

use Slim\Slim;
use \Stash\Session as StashSession;

use Flagship\Container;
use Flagship\Middleware\RouteMiddleware;


class SlimBootstrap {

    protected $app;
    protected $container;

    public function __construct(Slim $app, Container $container) {
        $this->app = $app;
        $this->container = $container;
        $this->container['logger']->debug('Path: ' . $this->app->request->getPathInfo());
        $this->container['profiler']->start('bootstrap.run');
    }

    public function bootstrap() {
        $app = $this->app;
        $container = $this->container;
        $app->setName($container['config']['name']);

        $this->setupHooks();
        $this->configureDevelopmentMode();
        $this->configureProductionMode();

        //////////////////////////
        // Object Configuration //
        //////////////////////////

        $container['slim.urlFor'] = $container->protect(function ($destination, $params) use ($app) {
            $getParams = $app->request->get();
            $url = $app->urlFor($destination, $params);
            if (count($getParams) == 0) {
                return $url;
            } else {
                $query = http_build_query($getParams);
                return $url . "?" . $query;
            }
        });

        $container->extend('offers', function ($offers, $c) use ($container) {
            $offers->setUrlFor(function ($route, $params) use ($container) {
                return $container['slim.urlFor']($route, $params);
            });
            return $offers;
        });

        // $container->extend('segment', function ($segment, $c) use ($app) {
            // $scripts = [];
            // // if ($app->view->has('scripts')) {
                // // $scripts = $app->view->get('scripts');
            // // }
            // // $scripts[] = $segment->scriptTag();
            // $app->view->set('scripts', $scripts);
            // return $segment;
        // });

        // $container['cookie.jar']->setSlimApp($app);
        $app->log->setWriter($container['logger']);
        $app->view($container['plates']);


        $app->container['custom.routes'] = function () use ($container) {
            return $container['custom.routes']->fetchAll();
        };

        ////////////////
        // Middleware //
        ////////////////

        RouteMiddleware::register($app, $container);

        // $app->add($container['middleware.scripts']);

        // $app->add(new \Flagship\Middleware\UserTracker(
            // $container
        // ));

        $app->add(new \Flagship\Middleware\Session(
            $container['session.cache'],
            $container['cookie.jar']
        ));

        $this->container['profiler']->stop('bootstrap.run');
        return $app;
    }

    public function setupHooks() {
        $app = $this->app;
        $container = $this->container;

        // Statsd Reporting
        // $timerMetricName = $container['librato.system']->totalName('request_time');
        // $app->hook("slim.before", function () use ($container, $timerMetricName) {
            // $container['librato.system']->total('num_requests');
            // $container['statsd']->startTiming($timerMetricName);
        // });

        // $app->hook("slim.after", function () use ($container, $timerMetricName) {
            // $container['statsd']->endTiming($timerMetricName);
            // $container['event.queue']->flushAll();
        // }, 10);

        // Custom URL handler
        $app->hook("slim.before", function () use ($app, $container) {
            $uri = $app->environment['PATH_INFO'];
            $custom = $app->container['custom.routes'];
            foreach ($custom as $c) {
                if ($c['url'] === $uri) {
                    $newUrl = $app->urlFor('landers', array('id' => $c['lander_id']));
                    $app->environment['PATH_INFO'] = $newUrl;
                }
            }
        }, 5);
    }

    public function configureDevelopmentMode() {
        $app = $this->app;
        $container = $this->container;
        $app->configureMode('development', function () use ($app, $container) {
            $app->add($container['debug.bar']);

            $app->hook("slim.before", function () use ($container) {
                $container['profiler']->start('slim.hooks');
            });

            $app->hook("slim.after.dispatch", function () use ($container) {
                $container['profiler']->stop('slim.hooks');
                // $container['profiler']->sinceBeginning('slim.done');
            }, 10);
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
                // $container['librato.system']->total('4xx');

                if (isset($fallback)) {
                    $app->redirect($container['slim.urlFor']('landers', array('id' => $fallback)));
                }
            });
        });
    }

}
