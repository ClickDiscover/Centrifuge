<?php

namespace Flagship\Storage;


class AerospikeNamespace {

    protected $namespace;
    protected $db;

    public function __construct(\Aerospike $db, $namespace) {
        $this->db = $db;
        $this->namespace = $namespace;
    }

    public function fetchById($key, $id) {
        $key = $this->db->initKey($this->namespace, $key, $id);
        $rc = $this->db->get($key, $rec);
        if ($rc == \Aerospike::OK) {
            return $rec;
        }
        return null;
    }

    public function putById($key, $id, $arr) {
        $key = $this->db->initKey($this->namespace, $key, $id);
        $rc = $this->db->put($key, $arr);
        if ($rc == \Aerospike::OK) {
            return true;
        } else {
            $log = \Flagship\Util\Logger::getInstance();
            $log->warn('Error', [$this->db->error(), $this->db->errorno()]);
        }
        return null;
    }

    public function db() {
        return $this->db;
    }
}
