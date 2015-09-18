<?php

namespace Flagship\Util;

trait Logging {

    protected $log;

    public function setLogger($logger) {
        $this->log = $logger;
    }

    protected function getLogger() {
        if (isset($this->log)) {
            return $this->log;
        }
        return Logger::getInstance();
    }
}
