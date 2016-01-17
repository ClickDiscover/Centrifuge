<?php

namespace Flagship\Middleware;

use League\Url\Url;
use Slim\Slim;
use Slim\Route;
use Flagship\Container;

class RouteMiddleware {

    public static function register(Slim $app, Container $container) {
        $app->container['route_middleware.click'] = self::closure($app, $container, "click");
    }

    public static function closure(Slim $app, Container $c, $name) {
        return function () use ($app, $c, $name) {
            return function ($route) use ($app, $c, $name) {
                return self::$name($app, $c, $route);
            };
        };
    }

    public static function click(Slim $app, Container $c, Route $route) {
        $lander = self::landerFromRequest($c['landers'], $app->request);
        $app->environment['referring.lander'] = $lander;
    }

    private static function landerFromRequest($landers, $req) {
        $id = self::landerIdFromRequest($req);
        if (is_null($id) && isset($_SESSION['last_lander'])) {
            return $_SESSION['last_lander'];
        }
        return (isset($id)) ? $landers->fetch($id) : null;
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

}
