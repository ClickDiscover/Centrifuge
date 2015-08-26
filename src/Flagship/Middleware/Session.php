<?php

namespace Flagship\Middleware;

use Slim\Middleware;
use Stash\Session as StashSession;

// class Session extends SessionM {
//     use \Flagship\Util\MiddlewareTrace;
// }

class Session extends Middleware {

    // const PREFIX = '_fp_';
    // public static $key = self::PREFIX . 'session';
    const SESSION_KEY = '_fp_session';

    protected $rootDomain;
    protected $lifetime;
    protected $path;
    protected $name;

    protected $secure = false;
    protected $httpOnly = false;

    public function __construct(
        $storage,
        $lifetime = 0,
        $rootDomain = "",
        $path = "/"
    ) {
        StashSession::registerHandler(new StashSession($storage));
        $this->lifetime = $lifetime;
        $this->path = $path;
        $this->rootDomain = $rootDomain;

        session_cache_limiter(false);
        session_name(self::SESSION_KEY);
        session_set_cookie_params(
            $this->lifetime,
            $this->path,
            $this->rootDomain,
            $this->secure,
            $this->httpOnly
        );
    }

    public function call() {
        $this->app->hook('slim.before', function() {
            session_start();
            $_SESSION['a'] = session_id();
        });
        $this->next->call();
    }
}
