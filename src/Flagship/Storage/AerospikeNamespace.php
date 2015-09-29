<?php

namespace Flagship\Storage;

use Flagship\Util\Logging;
use Flagship\Util\FunctionQueueInterface;
use Flagship\Util\FunctionQueueTrait;
use Flagship\Util\Profiler\Profiling;


class AerospikeNamespace implements FunctionQueueInterface {

    use Logging, FunctionQueueTrait;

    protected $namespace;
    protected $db;

    public function __construct(\Aerospike $db, $namespace) {
        $this->db = $db;
        $this->namespace = $namespace;
        $this->setProfilingClass('AerospikeNamespace');
    }

    public function fetchById($key, $id) {
        $digest = $this->db->initKey($this->namespace, $key, $id);

        $key = 'fetch_'.$key;
        $this->startTiming($key, $id);
        $rc = $this->db->get($digest, $rec);
        $this->stopTiming($key, $id);
        if ($this->checkResponseCode(__FUNCTION__, $id, $rc)) {
            return $rec;
        }
        return null;
    }

    public function putById($key, $id, $arr) {
        // $this->startTiming($key, $id);
        $digest = $this->db->initKey($this->namespace, $key, $id);
        $this->enqueue(function () use ($digest, $arr, $id) {
            $rc = $this->db->put($digest, $arr);
            $this->checkResponseCode(__FUNCTION__, $id, $rc);
        }, 'put_'.$key, $id);
    }

    protected function checkResponseCode($funcName, $id, $rc) {
        $log = $this->getLogger();
        if ($rc == \Aerospike::OK) {
            return true;
        } elseif ($rc == \Aerospike::ERR_RECORD_NOT_FOUND) {
            $log->info('Aerospike record not found', [$funcName, $id]);
            return false;
        } else {
            $log->warn('Aerospike not OK', [$funcName, $id, $this->db->error(), $this->db->errorno()]);
            return false;
        }
    }

    public function db() {
        return $this->db;
    }

    public function getNamespace() {
        return $this->namespace;
    }
}
