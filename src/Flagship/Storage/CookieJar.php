<?php

namespace Flagship\Storage;

use Symfony\Component\HttpFoundation\Cookie;
use Flagship\Util\ImmutableProperties;


class CookieJar {

    use ImmutableProperties;

    // Piwik: Life of the session cookie (in sec)
    // public $referralCookieTimeout = 15768000; // 6 months

    const MINUTES   = 1800;     // 30 minutes
    const MONTHS    = 33955200; // 13 months (365 + 28 days)
    const HALF_YEAR = 15768000; // 6 months

    protected $sessionLifetime;
    protected $visitorLifetime;
    protected $path;
    protected $domain;
    protected $secure = false;
    protected $httpOnly = true;
    protected $app;

    public function __construct (
        $domain,
        $sessionLifetime,
        $visitorLifetime
    ) {
        $this->domain = $domain;
        // Should check valid domain
        $this->domain = "";
        $this->path = '/';
        $this->sessionLifetime = $sessionLifetime;
        $this->visitorLifetime = $visitorLifetime;
    }

    public function setSlimApp($app) {
        $this->app = $app;
    }

    public function setCookie($key, $value, $expires = 0) {
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
}

