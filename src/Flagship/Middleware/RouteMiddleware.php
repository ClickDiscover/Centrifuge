<?php

namespace Flagship\Middleware;

use Slim\Slim;
use Slim\Route;
use Flagship\Container;

class RouteMiddleware {

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

    public static function view(Slim $app, Container $c, Route $route) {
        $app->environment['view'] = self::base($app, $c, "\Flagship\Event\View");
    }

    public static function click(Slim $app, Container $c, Route $route) {
        $ev = self::base($app, $c, "\Flagship\Event\Click");
        $app->environment['click'] = $ev;
    }
}
