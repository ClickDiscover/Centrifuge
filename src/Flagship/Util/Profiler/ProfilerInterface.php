<?php

namespace Flagship\Util\Profiler;


interface ProfilerInterface {
    public function start($name);
    public function stop ($name);
    public function sinceBeginning ($name);
    public function add($name, $start, $end);
}
