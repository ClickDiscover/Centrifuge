<?php

namespace Flagship\Storage;

use Flagship\Util\Logging;
use Flagship\Util\FunctionQueueInterface;
use Flagship\Util\FunctionQueueTrait;


class AerospikeNamespace implements FunctionQueueInterface {

    use FunctionQueueTrait;
    use Logging;

    protected $namespace;
    protected $db;

    public function __construct(\Aerospike $db, $namespace) {
        $this->db = $db;
        $this->namespace = $namespace;
    }

    public function fetchById($key, $id) {
        $key = $this->db->initKey($this->namespace, $key, $id);
        $rc = $this->db->get($key, $rec);
        if ($this->checkResponseCode("fetchById", $rc)) {
            return $rec;
        }
        return null;
    }

    public function putById($key, $id, $arr) {
        $key = $this->db->initKey($this->namespace, $key, $id);
        $this->enqueue(function () use ($key, $arr) {
            $rc = $this->db->put($key, $arr);
            $this->checkResponseCode("putById", $rc);
        });
    }

    protected function checkResponseCode($funcName, $rc) {
        if ($rc == \Aerospike::OK) {
            return true;
        } else {
            $log = $this->getLogger();
            $log->warn('Aerospike not OK', [$funcName, $this->db->error(), $this->db->errorno()]);
            return false;
        }
    }

    public function db() {
        return $this->db;
    }
}
