<?php

namespace Flagship\Middleware;

use League\Url\Url;
use Slim\Slim;
use Slim\Route;
use Flagship\Container;

class RouteMiddleware {

    public static function register(Slim $app, Container $container) {
        $app->container['route_middleware.click'] = self::closure($app, $container, "click");
        $app->container['route_middleware.view'] = self::closure($app, $container, "view");
        if ($app->config('debug')) {
            $app->container['route_middleware.viewAdmin'] = self::closure($app, $container, "viewAdmin");
            $app->container['route_middleware.clickAdmin'] = self::closure($app, $container, "clickAdmin");
        }
    }

    public static function closure(Slim $app, Container $c, $name) {
        return function () use ($app, $c, $name) {
            return function ($route) use ($app, $c, $name) {
                return self::$name($app, $c, $route);
            };
        };
    }

    public static function base(Slim $app, Container $c, $eventClass) {
        $eventId = $c['random.id'];
        $tracking  = $app->environment['tracking'];
        $cookie  = $tracking['cookie'];
        $userId = $cookie->getId();
        $event = new $eventClass($eventId, $userId);
        $event->setContext($tracking['test.context']);
        $event->setGoogleId($tracking['google.id']);
        $event->setCookie($cookie);
        return $event;
    }

    public static function viewAdmin(Slim $app, Container $c, Route $route) {
        // echo '<pre>';
        // var_dump($route->getParams()) . PHP_EOL . PHP_EOL;
        // echo '</pre>';
        $app->environment['view'] = self::base($app, $c, "\Flagship\Event\View");
    }

    public static function view(Slim $app, Container $c, Route $route) {
        self::viewAdmin($app, $c, $route);
        $id = $route->getParam("id");
        $lander = $c['landers']->fetch($id);
        if (!$lander) {
            $app->notFound();
        }
        $app->environment['view']->setLander($lander);
        $_SESSION['last_lander'] = $lander;
    }

    public static function clickAdmin(Slim $app, Container $c, Route $route) {
        $app->environment['click'] = self::base($app, $c, "\Flagship\Event\Click");
    }

    public static function click(Slim $app, Container $c, Route $route) {
        self::clickAdmin($app, $c, $route);
        $lander = self::landerFromRequest($c['landers'], $app->request);
        $app->environment['click']->setStepId($route->getParam('stepId'));
        $app->environment['click']->setLander($lander);
    }

    protected static function landerIdFromRequest($req) {
        $qs    = $req->get('fp_lid');
        $refer = $req->getReferrer();
        if (isset($refer)) {
            $refer = Url::createFromUrl($refer);
            $path = $refer->getPath()->toArray();
            $id = array_pop($path);
            if ($refer->getHost() == $req->getHost()) {
                return $id;
            }
        } elseif (isset($qs)) {
            return $qs;
        }
    }

    public static function landerFromRequest($landers, $req) {
        $id = landerIdFromRequest($req);
        if (is_null($id) && isset($_SESSION['last_lander'])) {
            return $_SESSION['last_lander'];
        }
        return (isset($id)) ? $landers->fetch($id) : null;
    }


}
