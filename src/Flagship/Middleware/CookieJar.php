<?php

namespace Flagship\Middleware;

use Symfony\Component\HttpFoundation\Cookie;

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

    // public function cookie(
}

