<?php

namespace Flagship\Middleware;

use Slim\Slim;
use Slim\Middleware;
use Slim\Route;

use Flagship\Container;
use Flagship\Util\Profiler\Profiling;


class ScriptMiddleware extends Middleware {

    use Profiling;

    protected $scripts = [];

    public function __construct(Container $container) {
        $this->setProfiler($container['profiler']);
        $this->setProfilingClass('ScriptMiddleware');
    }

    public function addScript($s) {
        $this->scripts[] = $s;
    }

    public function call () {
        $this->next->call();

        $res = $this->app->response;
        if(!$res->isSuccessful()) {
            return false;
        }

        if (stripos($res->header('Content-Type'), 'html') !== false) {
            $newHtml = $this->insertScriptTags($res->body());
            $res->body($newHtml);
        }
    }

    protected function insertScriptTags($html) {
        $pos = mb_strripos($html, '</body>');
        $tags = $this->buildScriptTags();
        if ($pos === false) {
            $html .= $tags;
        } else {
            $html = mb_substr($html, 0, $pos) . $tags . mb_substr($html, $pos);
        }
        return $html;
    }

    protected function buildScriptTags() {
        return implode("\n", $this->scripts);
    }

}
