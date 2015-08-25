<?php

namespace Flagship\Middleware;

use \Slim\Middleware;
use \Hashids\Hashids;

use Flagship\Event\BaseEvent;
use Flagship\Middleware\Session;


class UserTracker extends Middleware {

    const PREFIX = '_fp_';

    protected $hasher;
    // protected $rootDomain;
    // protected $cookieLifetime;
    // protected $cookiePath;

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
            // $tracking['user.id'] = new_random_id();
            $tracking['visit.id'] = $this->hasher->encode([$requestTime]);
        }


        $ev = new BaseEvent($req);
        $tracking['url'] = $ev->urlContext->toCleanArray();
        $tracking['user'] = $ev->userContext->toCleanArray();
        $app->view->set('tracking', $tracking);

        $this->next->call();
    }
}
