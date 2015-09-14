<?php

namespace Flagship\Util;


interface FunctionQueueInterface {
    public function enqueue(\Closure $callback);
    public function flushQueue();
}


