<?php

namespace Flagship\Middleware;

use \Slim\Middleware;
use \Hashids\Hashids;

use Flagship\Event\BaseEvent;
use Flagship\Middleware\Session;


class UserTracker extends Middleware {

    protected $hasher;

    public function __construct($hasher) {
        $this->hasher = $hasher;
    }

    public function call() {
        $app = $this->app;
        $req = $this->app->request;
        $sessionIdCookie = $app->getCookie(Session::SESSION_KEY);

        $tracking = [];
        $requestTime = $_SERVER['REQUEST_TIME'];
        $tracking['visit.time'] = $requestTime;

        if (isset($sessionIdCookie)) {
            $tracking['user.id'] = $sessionIdCookie;
            $tracking['visit.id'] = $this->hasher->encode([$sessionIdCookie, $requestTime]);
        } else {
            $tracking['visit.id'] = $this->hasher->encode([$requestTime]);
        }


        $ev = new BaseEvent($req);
        $tracking['url'] = $ev->urlContext->toCleanArray();
        $tracking['user'] = $ev->userContext->toCleanArray();
        $app->view->set('tracking', $tracking);

        $this->next->call();
    }
}
