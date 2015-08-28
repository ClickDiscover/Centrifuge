<?php

namespace Flagship\Middleware;

use \Slim\Middleware;

use Flagship\Event\BaseEvent;
use Flagship\Middleware\Session;
use Flagship\Storage\CookieJar;



// class UserTracker extends UserTrackerM {
//     use \Flagship\Util\MiddlewareTrace;
// }

class UserTracker extends Middleware {

    const VISITOR_KEY = '_fp_id';

    protected $cookieJar;
    protected $trackingCookie = null;

    public function __construct($cookieJar) {
        $this->cookieJar = $cookieJar;

        // $this->currentTs = time();
        // $this->createTs = $this->currentTs;
        // $this->visitCount = 0;
        // $this->currentVisitTs = false;
        // $this->lastVisitTs = false;
        // $this->ecommerceLastOrderTimestamp = false;
    }

    public function call() {
        $this->app->hook('slim.before.dispatch', [$this, 'setUserId']);
        $this->next->call();
        $this->trackingCookie->incrementVisitCount();
        $this->cookieJar->setTracking($this->trackingCookie);
    }


    public function setUserId() {
        $app = $this->app;
        $req = $this->app->request;
        $tracking = [];
        $requestTime = $_SERVER['REQUEST_TIME'];

        // $sessionCookie = $app->getCookie(Session::SESSION_KEY);
        $this->trackingCookie = $this->cookieJar->getOrCreateTracking();
        // $tracking['cookie'] = $this->trackingCookie;
        $tracking['cookie'] = $this->trackingCookie->pretty();




        $ev = new BaseEvent($req);
        $tracking['url'] = $ev->urlContext->toCleanArray();
        $tracking['user'] = $ev->userContext->toCleanArray();
        $app->view->set('tracking', $tracking);
    }
}
