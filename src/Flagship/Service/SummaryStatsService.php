<?php

namespace Flagship\Storage;

use Flagship\Model\Lander;
use Flagship\Event\AbstractEvent;
use Flagship\Event\Click;

use Flagship\Util\Logging;
use Flagship\Util\FunctionQueueInterface;
use Flagship\Util\FunctionQueueTrait;
use Flagship\Util\Profiler\Profiling;


class SummaryStatsService {

    // use Logging, FunctionQueueTrait;

    protected $namespace;
    protected $db;
    protected $interval;

    public function __construct(AerospikeNamespace $db, $interval = 3600) {
        $this->db = $db->db();
        $this->namespace = $db->getNamespace();
        $this->interval = $interval;
    }

    public function trackLander(Lander $lander, string $type) {
        $now = $time();
        $bucket = $now - ($now % $this->interval);
        $key = "{$type}:{$bucket}:lander.{$lander->id}";
        $this->db->initKey($this->namespace, $key, 'total');
    }
}

