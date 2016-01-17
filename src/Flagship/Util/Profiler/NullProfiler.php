<?php

namespace Flagship\Util\Profiler;


class NullProfiler implements ProfilerInterface {
    public function stop ($name)               {}
    public function start($name)               {}
    public function add  ($name, $start, $end) {}
    public function sinceBeginning ($name)     {}
}
