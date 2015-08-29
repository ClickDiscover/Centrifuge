<?php

namespace Flagship\Util;

trait Logging {

    protected $log;

    public function setLogger($logger) {
        $this->log = $logger;
    }
}
