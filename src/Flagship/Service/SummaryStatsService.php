<?php

namespace Flagship\Storage;

use Flagship\Model\Lander;
use Flagship\Event\AbstractEvent;
use Flagship\Event\Click;

use Flagship\Util\Logging;
use Flagship\Util\FunctionQueueInterface;
use Flagship\Util\FunctionQueueTrait;
use Flagship\Util\Profiler\Profiling;


class SummaryStatsService implements FunctionQueueInterface {

    use Logging, FunctionQueueTrait;

    protected $namespace;
    protected $db;
    protected $interval;

    public function __construct(\Aerospike $db, $namespace, $interval = 3600) {
        $this->setProfilingClass('SummaryStatsService');
        $this->db = $db;
        $this->namespace = $namespace;
        $this->interval = $interval;
    }

    public function trackLander(Lander $lander) {

    }
}

