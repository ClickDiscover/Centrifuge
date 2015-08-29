<?php

namespace Flagship\Storage;

use Slim\Slim;
use Hashids\Hashids;

use Flagship\Middleware\Session;
use Flagship\Util\ImmutableProperties;


class CookieJar {

    use ImmutableProperties;

    // Piwik: Life of the session cookie (in sec)
    // public $referralCookieTimeout = 15768000; // 6 months

    const MINUTES   = 1800;     // 30 minutes
    const MONTHS    = 33955200; // 13 months (365 + 28 days)
    const HALF_YEAR = 15768000; // 6 months

    protected $hasher;
    protected $sessionLifetime;
    protected $visitorLifetime;
    protected $path;
    protected $domain;
    protected $secure = false;
    protected $httpOnly = true;
    protected $app;

    public function __construct (
        Hashids $hasher,
        $domain,
        $sessionLifetime,
        $visitorLifetime
    ) {
        $this->hasher = $hasher;
        // Should check valid domain
        $this->domain = $domain;
        $this->path = '/';
        $this->sessionLifetime = $sessionLifetime;
        $this->visitorLifetime = $visitorLifetime;
    }

    public function setSlimApp(Slim $app) {
        $this->app = $app;
    }

    public function setCookie($key, $value, $expires = 0) {
        if ($expires > 0) {
            $expires += time();
        }
        if (isset($this->app)) {
            $this->app->setCookie(
                $key,
                $value,
                $expires,
                $this->path,
                $this->domain,
                $this->secure,
                $this->httpOnly
            );
        }

        return $this->cookieArray($key, $value, $expires);
    }

    protected function cookieArray($key, $value, $expires = 0) {
        return array(
            'key'      => $key,
            'value'    => $value,
            'expires'  => $expires,
            'path'     => $this->path,
            'domain'   => $this->domain,
            'secure'   => $this->secure,
            'httpOnly' => $this->httpOnly
        );
    }

    // Ordering is important, call after session_name and before session_start
    public function setSessionCookieParams() {
        session_set_cookie_params(
            $this->sessionLifetime,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
        );
    }

    public function getOrCreateTracking() {
        if (empty($this->app)) {
            return false;
        }

        $t = TrackingCookie::getOrCreate(
            $this->app->getCookie(TrackingCookie::KEY),
            $this->hasher
        );

        $visitId = $this->getVisitId();
        // This is a weird state
        // Session should have always been started by now
        if(empty($visitId)) {
        }
        if (isset($t)) {
            $t->setVisitId($visitId);
        }
        return $t;
    }

    public function setTracking(TrackingCookie $tc) {
        if (empty($this->app)) {
            return false;
        }

        $this->setCookie(TrackingCookie::KEY, $tc->toCookie(), $this->visitorLifetime);
    }

    public function getVisitId() {
        return $_SESSION[Session::SESSION_KEY];
    }

    public function getCookie ($name, $deleteIfInvalid = true) {
        if (empty($this->app)) {
            return false;
        }

        return $this->app->getCookie($name, $deleteIfInvalid);
    }
}

