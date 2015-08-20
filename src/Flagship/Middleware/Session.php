<?php

namespace Flagship\Middleware;

use Slim\Middleware;
use Stash\Session as StashSession;

class Session extends Middleware {

    protected $options = [
        'name' => '_fp_session',
        'lifetime' => 0,
        'path' => null,
        'domain' => null,
        'secure' => false,
        'httponly' => true,
    ];

    public function __construct($cache, $options = []) {
        $keys = array_keys($this->options);
        foreach ($keys as $key) {
            if (array_key_exists($key, $options)) {
                $this->options[$key] = $options[$key];
            }
        }
        StashSession::registerHandler(new StashSession($cache));
    }

    public function call() {
        $options = $this->options;
        $req = $this->app->request;
        // $current = session_get_cookie_params();
        // $lifetime = (int)($options['lifetime'] ?: $current['lifetime']);
        // $path     = $options['path'] ?: $current['path'];
        // $domain   = $options['domain'] ?: $current['domain'];
        // $secure   = (bool)$options['secure'];
        // $httponly = (bool)$options['httponly'];
        // session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
        session_name($options['name']);
        // session_cache_limiter(false); //http://docs.slimframework.com/#Sessions
        session_start();
        $this->next->call();
    }
}
