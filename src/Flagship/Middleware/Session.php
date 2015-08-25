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
        // ini_set('session.use_cookies', 0);
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
        // $this->end();
    }

    // public function setupSession() {
    //     $this->app->view->set('cookie.ses', session_get_cookie_params());
    //     session_start();

        // $id = $this->app->getCookie(self::SESSION_KEY);
        // if (!isset($id)) {
        //     $this->name= $this->hasher->randomId();
        //     // session_id($randomId);
        //     session_start();
        // }

        // echo "session_start" . PHP_EOL;

        // echo "dump" . PHP_EOL;
        // var_dump([
        //     'php id' => session_id(),
        //     'php name' => session_name(),
        //     'cookie id' => $id,
        //     'cookie isset' => (isset($id))
        // ]);
        // print_r($this->app->request->cookies);
        // echo "</pre>";
    // }

    // public function end() {
    //     $this->app->setCookie(
    //         self::SESSION_KEY,
    //         $this->name,
    //         $this->lifetime,
    //         $this->path,
    //         $this->rootDomain
    //     );
    // }

    // protected function randomId() {
    //     $r = sha1(uniqid('', true).$this->hasher->encode([rand()]).microtime(true));
    //     return $r;
    // }
}
