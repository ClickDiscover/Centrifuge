<?php

namespace Flagship\Middleware;

use \Slim\Middleware;
use \Hashids\Hashids;

use Flagship\Event\BaseEvent;
use Flagship\Middleware\Session;
use Flagship\Storage\CookieJar;



// class UserTracker extends UserTrackerM {
//     use \Flagship\Util\MiddlewareTrace;
// }

class UserTracker extends Middleware {

    const VISITOR_COOKIE = '_fp_id';

    protected $hasher;
    protected $cookieJar;

    public function __construct($cookieJar, $hasher) {
        $this->cookieJar = $cookieJar;
        $this->hasher = $hasher;

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
    }


    public function setUserId() {
        $app = $this->app;
        $req = $this->app->request;
        $tracking = [];
        $requestTime = $_SERVER['REQUEST_TIME'];

        $sessionCookie = $app->getCookie(Session::SESSION_KEY);
        $sessionId = $_SESSION[Session::SESSION_KEY];

        $visitorCookie = $app->getCookie(self::VISITOR_COOKIE);

        $visitorId = null;
        $sessionId = null;

        if (!isset($visitorCookie)) {
            $visitorId = $this->hasher->encode(time());
            $vcPre = $this->cookieJar->setCookie(self::VISITOR_COOKIE, $visitorId);
            $tracking['visitor.cookie.pre'] = $vcPre;
        } else {
            $visitorId = $visitorCookie;
        }

        $tracking['visit.time'] = $requestTime;
        $tracking['visitor.cookie'] = $visitorCookie;
        $tracking['foo'] = !isset($_SESSION['foo']);

        if (isset($sessionId)) {
            $tracking['session.id'] = $sessionId;
        }

        if (isset($sessionCookie)) {
            $tracking['session.cookie'] = $sessionCookie;
        }


        // if (isset($cookieId)) {
        //     $tracking['user.cookie.id'] = $cookieId;
        //     $tracking['visit.id'] = $this->hasher->encode([$cookieId, $requestTime]);
        // } else {
        //     $tracking['user.session.id'] = $sessionId;
        //     $tracking['visit.id'] = $this->hasher->encode([$requestTime]);
        // }


        $ev = new BaseEvent($req);
        $tracking['url'] = $ev->urlContext->toCleanArray();
        $tracking['user'] = $ev->userContext->toCleanArray();
        $app->view->set('tracking', $tracking);
    }
}
