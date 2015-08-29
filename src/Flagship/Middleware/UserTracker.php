<?php

namespace Flagship\Middleware;

use \Slim\Middleware;

use Flagship\Event\EventFactory;
use Flagship\Middleware\Session;
use Flagship\Storage\CookieJar;


class UserTracker extends Middleware {

    const VISITOR_KEY = '_fp_id';

    protected $config;
    protected $cookieJar;
    protected $trackingCookie = null;
    protected $events;

    public function __construct(CookieJar $cookieJar, EventFactory $events) {
        $this->cookieJar = $cookieJar;
        $this->events = $events;
    }

    public function call() {
        // A little bit of a kludge, should be in Container.php
        $this->config = $this->app->config('tracking');

        $this->app->hook('slim.before', [$this, 'before']);
        $this->next->call();
        $this->after();
    }

    public function before() {
        $req = $this->app->request;
        $env = $this->app->environment();

        $tracking = [];
        $ev = $this->events->createFromRequest($req);
        $ev->finalize();
        $tracking['context'] = $ev;
        $tracking['google.id'] = $this->checkGACookie();

        // Get or create tracking cookie
        $tc = $this->cookieJar->getOrCreateTracking();
        if (empty($tc)) {
            $this->app->log->warn('Warning tracking cookie is not set');
        } else {
            $this->trackingCookie = $tc;
            $tracking['debug'] = ['cookie' => $this->trackingCookie->pretty()];
            $tracking['cookie'] = $this->trackingCookie;
            $tracking['flagship.id']    = $this->trackingCookie->getId();
        }

        // Set tracking information on app environment
        $env['tracking']       = $tracking;
    }

    public function after () {
        if(isset($this->trackingCookie)) {
            $this->trackingCookie->incrementVisitCount();
            $this->cookieJar->setTracking($this->trackingCookie);
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
