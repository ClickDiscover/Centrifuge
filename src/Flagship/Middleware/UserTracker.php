<?php

namespace Flagship\Middleware;

use \Slim\Middleware;

use Flagship\Event\BaseEvent;
use Flagship\Event\EventContextFactory;
use Flagship\Middleware\Session;
use Flagship\Storage\CookieJar;
use \Flagship\Storage\AerospikeNamespace;
use Flagship\Model\User;


class UserTracker extends Middleware {

    protected $aerospike;
    protected $cookieJar;
    protected $trackingCookie = null;
    protected $events;

    public function __construct(CookieJar $cookieJar, EventContextFactory $events, AerospikeNamespace $aerospike) {
        $this->cookieJar = $cookieJar;
        $this->events = $events;
        $this->aerospike = $aerospike;
    }

    public function call() {
        $this->app->hook('slim.before', [$this, 'before']);
        $this->app->hook('slim.after.dispatch', [$this, 'after'], 1);
        $this->next->call();
    }

    public function before() {
        $this->trackingCookie = $this->cookieJar->getOrCreateTracking();
        if (empty($this->trackingCookie)) {
            $this->app->log->warn('Warning tracking cookie is not set');
            return false;
        }

        $ga = $this->checkGACookie();
        $this->user = User::fromAerospike(
            $this->aerospike,
            $this->trackingCookie,
            $ga
        );
        $this->app->environment['user'] = $this->user;
        $this->app->environment['contexts'] = $this->events->createFromRequest($this->app->request);
    }

    public function after () {
        if(isset($this->trackingCookie)) {
            $this->trackingCookie->incrementVisitCount();
            $this->cookieJar->setTracking($this->trackingCookie);
        }

        if (isset($this->user)) {
            $rc = $this->user->toAerospike($this->aerospike);
        }
    }

    // Set google analytics ID on env for segment integration
    protected function checkGACookie() {
        $ga = $this->cookieJar->getCookie('_ga');
        if (isset($ga)) {
            $parts = explode('.', $ga);
            $gaID = $parts[count($parts)-2].'.'.$parts[count($parts)-1];
            return $gaID;
        }
    }
}

class UserTrackerContext {
    public $context;
    public $googleId;
    public $cookie;

    public function __construct($cookie, $context, $googleId = null) {
        $this->cookie = $cookie;
        $this->context = $context;
        $this->googleId = $googleId;
    }
}
