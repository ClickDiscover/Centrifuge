<?php

namespace Flagship\Middleware;

use \Slim\Middleware;
use \Hashids\Hashids;

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
        // $requestTime = $app->environment['REQUEST_TIME'];
        $requestTime = $_SERVER['REQUEST_TIME'];
        $tracking['visit.id'] = $this->hasher->encode($requestTime);
        $tracking['visit.time'] = $requestTime;

        if (isset($sessionIdCookie)) {
            $tracking['user.id'] = $sessionIdCookie;
        }

        $app->view->set('tracking', $tracking);

        $this->next->call();
    }
}
