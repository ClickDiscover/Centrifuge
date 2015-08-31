<?php

namespace Flagship\Middleware;

use Slim\Middleware;
use Stash\Session as StashSession;

// class Session extends SessionM {
//     use \Flagship\Util\MiddlewareTrace;
// }

class Session extends Middleware {

    const SESSION_KEY = '_fp_visit_id';

    protected $cookieJar;

    public function __construct($storage, $cookieJar) {
        StashSession::registerHandler(new StashSession($storage));
        $this->cookieJar = $cookieJar;

        session_cache_limiter(false);
        session_name(self::SESSION_KEY);
        $this->cookieJar->setSessionCookieParams();
    }

    public function call() {
        $this->app->hook('slim.before', function() {
            // Automatic setting of cookies
            // Slim is not aware of this!
            session_start();

            // Write session data because user tracker activates next
            // We can't wait until setcookie because that will be on the next request
            $_SESSION[self::SESSION_KEY] = session_id();
        });
        $this->next->call();
    }
}
