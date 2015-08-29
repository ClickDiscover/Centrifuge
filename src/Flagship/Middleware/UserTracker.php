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

        $this->trackingCookie = $this->cookieJar->getOrCreateTracking();
        $ev = $this->events->createFromRequest($req);

        // Set tracking information on app environment
        $tracking              = [];
        $tracking['cookie']    = $this->trackingCookie->pretty();
        $tracking['flagship.id']    = $this->trackingCookie->getId();
        $tracking['context'] = $ev->toArray();
        $tracking['google.id'] = $this->checkGACookie();

        $env['tracking']       = $tracking;
        // $this->app->view->set('tracking', $tracking);
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
