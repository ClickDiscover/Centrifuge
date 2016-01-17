<?php

namespace Flagship\Event;

use Flagship\Util\FunctionQueueInterface;


class EventQueueManager {

    protected $queues = [];

    public function __construct() {
        return $this;
    }

    public function addStorage(FunctionQueueInterface $storage) {
        $this->queues[] = $storage;
        return $this;
    }

    public function flushAll() {
        foreach ($this->queues as $q) {
            $q->flushQueue();
        }
    }
}
