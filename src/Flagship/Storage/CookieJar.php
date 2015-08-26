<?php

namespace Flagship\Storage;

use Symfony\Component\HttpFoundation\Cookie;
use Flagship\Util\ImmutableProperties;


class CookieJar {

    use ImmutableProperties;

    const MINUTES   = 1800;     // 30 minutes
    const MONTHS    = 33955200; // 13 months (365 + 28 days)
    const HALF_YEAR = 15768000; // 6 months

    protected $prefix = '_fp_';
    protected $sessionLifetime;
    protected $visitorLifetime;
    protected $path;
    protected $domain;
    protected $secure = false;
    protected $httpOnly = true;

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


    public function makeCookie($key, $value, $expires = 0) {
        return new Cookie(
            $prefix . $key,
            $value,
            $expires,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
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

