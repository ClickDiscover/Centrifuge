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

    //////////////
    // Tracking //
    //////////////

    public static function base(Slim $app, Container $c, $eventClass) {
        $eventId = $c['random.id'];
        $user = $app->environment['user'];
        $event = new $eventClass($eventId, $user);
        $event->setContext($app->environment['contexts']);
        return $event;
    }

    // public static function track(BaseEvent $ev, Container $centrifuge) {
    //     $ev->toLibrato($centrifuge['librato.performance']);
    //     $ev->toSegment($centrifuge['segment']);
    //     $ev->toAerospike($centrifuge['aerospike']);
    // }

    public static function view(Slim $app, Container $c, Route $route) {
        $view = self::base($app, $c, "\Flagship\Event\View");
        $id = $route->getParam("id");
        $lander = $c['landers']->fetch($id);
        if (!$lander) {
            $app->notFound();
        }
        $view->setLander($lander);
        $app->environment['user']->appendView($view);
        $_SESSION['last_lander'] = $lander;
        // $this->track($view, $c);
        self::callCookieMethod($view, 'setLastVisitTime');
        $app->environment['view'] = $view;
    }

    public static function click(Slim $app, Container $c, Route $route) {
        $click = self::base($app, $c, "\Flagship\Event\Click");
        $lander = self::landerFromRequest($c['landers'], $app->request);
        $click->setStepId($route->getParam('stepId'));
        $click->setLander($lander);
        $app->environment['user']->appendClick($click);
        print_r(['click middleware', $click->getId()]);
        self::callCookieMethod($click, 'setLastOfferClickTime');
        $app->environment['click'] = $click;
        // $this->track($click, $c);
    }

    /////////////////////
    // Admin & Helpers //
    /////////////////////

    private static function callCookieMethod($ev, $method) {
        $cookie = $ev->getCookie();
        if (isset($cookie)) {
            $cookie->$method(time());
        }
    }

    public static function viewAdmin(Slim $app, Container $c, Route $route) {
        $view = self::base($app, $c, "\Flagship\Event\View");
        $app->environment['view'] = $view;
        return $view;
    }

    public static function clickAdmin(Slim $app, Container $c, Route $route) {
        $click = self::base($app, $c, "\Flagship\Event\Click");
        $app->environment['click'] = $click;
        return $click;
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
