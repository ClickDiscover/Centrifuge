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
            try {
                $this->app->redirect($route);
            } catch (\Slim\Exception\Stop $e) {
                $this->app->log->info('404 - falling back ' . print_r(array(
                    'path' => $this->app->request->getPath()
                ), true));
            }
        }
    }
}
