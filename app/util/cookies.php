<?php

use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;


class CookieJar {

    protected $prefix;
    protected $defaultPath;
    protected $domain;
    protected $encrypt;
    protected $cookies;
    protected $queue = [];

    public function __construct($cookies, $domain = null, $prefix = '_fp_', $defaultPath = '/') {
        $this->cookies = $cookies;
        $this->domain = $domain;
        $this->prefix = $prefix;
        $this->defaultPath = $defaultPath;
        $this->encrypt = false;
    }

    public function setCookies($cs) {
        $this->cookies = array_merge($this->cookies, $cs);
    }

    public function get($name, $default = "") {
        if (isset($this->cookies[$this->getCookieName($name)])) {
            return $this->cookies[$this->getCookieName($name)];
        }
        return $default;
    }

    // Laraval CookieJar shit
    public function add($name, $value, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true) {
        $name = $this->getCookieName($name);
        list($path, $domain) = $this->getPathAndDomain($path, $domain);
        $time = ($minutes == 0) ? 0 : time() + ($minutes * 60);
        $this->queue[$name] = new SymfonyCookie($name, $value, $time, $path, $domain, $secure, $httpOnly);
    }

    public function addForever($name, $value, $path = null, $domain = null, $secure = false, $httpOnly = true) {
        return $this->make($name, $value, 2628000, $path, $domain, $secure, $httpOnly);
    }

    protected function getPathAndDomain($path, $domain) {
        return [$path ?: $this->defaultPath, $domain ?: $this->domain];
    }

    // Piwik Stuff
    protected function getCookieName($cookieName) {
        // NOTE: If the cookie name is changed, we must also update the method in piwik.js with the same name.
        // $hash = substr( sha1( ($this->configCookieDomain == '' ? self::getCurrentHost() : $this->configCookieDomain)  . $this->configCookiePath ), 0, 4);
        return $this->prefix . $cookieName;
    }



    public function set() {
        if(!headers_sent()) {
            foreach ($this->queue as $cookie) {
                header("Set-Cookie: " . $cookie);
            }
        }
    }

    // static public function domainFixup($domain)
    // {
    //     $dl = strlen($domain) - 1;
    //     // remove trailing '.'
    //     if ($domain{$dl} === '.') {
    //         $domain = substr($domain, 0, $dl);
    //     }
    //     // remove leading '*'
    //     if (substr($domain, 0, 2) === '*.') {
    //         $domain = substr($domain, 1);
    //     }
    //     return $domain;
    // }

    // protected function loadVisitorIdCookie()
    // {
    //     $idCookie = $this->getCookieMatchingName('id');
    //     if ($idCookie === false) {
    //         return false;
    //     }
    //     $parts = explode('.', $idCookie);
    //     if (strlen($parts[0]) != self::LENGTH_VISITOR_ID) {
    //         return false;
    //     }
    //     $this->cookieVisitorId = $parts[0]; // provides backward compatibility since getVisitorId() didn't change any existing VisitorId value
    //     $this->createTs = $parts[1];
    //     $this->visitCount = (int)$parts[2];
    //     $this->currentVisitTs = $parts[3];
    //     $this->lastVisitTs = $parts[4];
    //     if(isset($parts[5])) {
    //         $this->ecommerceLastOrderTimestamp = $parts[5];
    //     }
    //     return true;
    // }
}
