<?php

namespace Flagship\Util;

use Flagship\Util\Profiler\Profiling;

trait FunctionQueueTrait {

    use Profiling;

    public $_funcs = [];

    public function enqueue(\Closure $callback, $namespace = null, $key = null) {
        $this->_funcs[] = [
            'cb' => $callback,
            'label' => $this->_key($namespace, $key)
        ];
    }

    public function flushQueue() {
        $res = [];
        foreach ($this->_funcs as $closure) {
            $label = $closure['label'];
            $cb = $closure['cb'];
            $this->getProfiler()->start($label);
            $res[] = $cb();
            $this->getProfiler()->stop($label);
        }
        return $res;
    }
}


