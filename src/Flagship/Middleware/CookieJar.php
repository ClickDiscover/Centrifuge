<?php

namespace Flagship\Middleware;


class Cookie {

    use ImmutableProperties;

    protected $key;
    protected $value;
    protected $lifetime = 0;
    protected $path     = null;
    protected $domain   = null;
    protected $secure   = false;
    protected $httpOnly = false;

    public function __construct(
        $key      = null,
        $value    = null,
        $lifetime = null,
        $path     = null,
        $domain   = null,
        $secure   = null,
        $httpOnly = null
    ) {
        $this->key      = $key;
        $this->domain   = $domain;
        $this->path     = $path     ?: $this->path;
        $this->lifetime = $lifetime ?: $this->lifetime;
        $this->secure   = $secure   ?: $this->secure;
        $this->httpOnly = $httpOnly ?: $this->httpOnly;
    }
}

class CookieJar {

    const MINUTES   = 1800;     // 30 minutes
    const MONTHS    = 33955200; // 13 months (365 + 28 days)
    const HALF_YEAR = 15768000; // 6 months


    protected $prefix = '_fp_';
    protected $defaultPath;
    protected $defaultDomain;

    public function __construct ($path = '/', $domain = '') {
        $this->defaultDomain = $domain;
        $this->defaultPath = $path;
    }


    public function f(){}
}


