<?php

namespace Flagship\Middleware;

use Slim\Middleware;

class LanderFallback extends Middleware {

    public function __construct($fallbackId) {
        $this->fallbackId = $fallbackId;
    }

    public function call() {
        $this->next->call();
        if (404 === $this->app->response->getStatus()) {
            $route = $this->app->urlFor('landers', array('id' => $this->fallbackId));
            $this->app->redirect($route);
        }
    }
}
