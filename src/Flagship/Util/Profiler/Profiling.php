<?php

namespace Flagship\Util\Profiler;


trait Profiling {
    protected $_class = '';
    protected $_namespace = '';

    protected $_profiler;

    public  function setProfiler($p) {
        $this->_profiler = $p;
    }

    protected function getProfiler() {
        if (empty($this->_profiler)) {
            $this->_profiler = new NullProfiler();
        }
        return $this->_profiler;
    }

    protected function setProfilingClass($c) {
        $this->_class = $c;
    }

    // protected function setNamespace($c) {
    //     $this->_namespace = $c;
    // }

    protected function _key($namespace, $key = null) {
        $namespace = empty($namespace) ? __CLASS__ : $namespace;
        $pk = $this->_class . '.' . $namespace;
        if (isset($key)) {
            $pk .= '::' . $key;
        }
        return $pk;
    }

    protected function startTiming($namespace, $key = null) {
        $this->getProfiler()->start($this->_key($namespace, $key));
    }

    protected function stopTiming($namespace, $key = null) {
        $this->getProfiler()->stop($this->_key($namespace, $key));
    }

}
