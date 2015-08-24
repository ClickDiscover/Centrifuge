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


        $app->notFound(function () use ($app, $container) {
            $fallback = $app->config('fallback_lander');

            $container['logger']->warning('4xx', [$app->request->getPathInfo()]);
            $container['librato.system']->total('4xx');

            if (isset($fallback)) {
                $app->redirect($app->urlFor('landers', array('id' => $fallback)));
            }
        });

        // $app->add(new \Flagship\Middleware\LanderFallback($container['config']['application']['fallback_lander']));

        return $app;
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
