<?php

namespace Flagship\Util;


trait FunctionQueueTrait {

    public $_funcs = [];

    public function enqueue(\Closure $callback) {
        $this->_funcs[] = $callback;
    }
    public function flushQueue() {
        $res = [];
        foreach ($this->_funcs as $cb) {
            $res[] = $cb();
        }
        return $res;
    }
}


