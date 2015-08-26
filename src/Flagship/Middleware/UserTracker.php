<?php

namespace Flagship\Middleware;

use \Slim\Middleware;
use \Hashids\Hashids;

use Flagship\Event\BaseEvent;
use Flagship\Middleware\Session;



// class UserTracker extends UserTrackerM {
//     use \Flagship\Util\MiddlewareTrace;
// }

class UserTracker extends Middleware {

    const PREFIX = '_fp_';


    protected $hasher;
    // protected $rootDomain;
    // protected $cookieLifetime;
    // protected $cookiePath;

    // Life of the visitor cookie (in sec)
    public $visitorCookieTimeout = 33955200; // 13 months (365 + 28 days)
    // Life of the session cookie (in sec)
    public $sessionCookieTimeout = 1800; // 30 minutes
    // Life of the session cookie (in sec)
    public $referralCookieTimeout = 15768000; // 6 months


    public function __construct($hasher) {
        $this->hasher = $hasher;

        // Visitor Ids in order
        // $this->userId = false;
        // $this->forcedVisitorId = false;
        // $this->cookieVisitorId = false;
        // $this->randomVisitorId = false;

        // $this->setNewVisitorId();

        // $this->configCookiesDisabled = false;
        // $this->configCookiePath = self::DEFAULT_COOKIE_PATH;
        // $this->configCookieDomain = '';

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
        $cookieId = $app->getCookie(Session::SESSION_KEY);
        $sessionId = $_SESSION[Session::SESSION_KEY];

        $tracking = [];
        $requestTime = $_SERVER['REQUEST_TIME'];
        $tracking['visit.time'] = $requestTime;

        if (isset($sessionId)) {
            $tracking['session.id'] = $sessionId;
        }

        if (isset($cookieId)) {
            $tracking['cookie.id'] = $cookieId;
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
