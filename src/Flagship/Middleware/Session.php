<?php

namespace Flagship\Middleware;

use Slim\Middleware;
use Stash\Session as StashSession;

class Session extends Middleware {

    const SESSION_KEY = '_fp_session';

    protected $name;
    // protected $options = [ ];

    public function __construct($cache, $name = self::SESSION_KEY, $options = []) {
        $this->name = $name;
        StashSession::registerHandler(new StashSession($cache));
    }

    public function call() {
        $req = $this->app->request;
        session_name($this->name);
        session_cache_limiter(false);
        session_start();
        $this->next->call();
    }
}
