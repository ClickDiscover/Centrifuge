<?php

namespace Flagship\Storage;

use Flagship\Container;
use \Flagship\Util\Logging;
use Flagship\Util\Profiler\Profiling;


class QueryCache {
    use Logging, Profiling;

    public $db;
    protected $cache;
    protected $expiration;

    public function __construct($db, $cache, $expiration) {
        $this->db = $db;
        $this->cache = $cache;
        $this->expiration = $expiration;
        $this->setProfilingClass('QueryCache');
    }

    public function cachedQuery($namespace, $callback) {
        $this->startTiming($namespace);
        $item = $this->cache->getItem($namespace);
        $result = $item->get();
        if($item->isMiss()) {
            $this->log->info("Cache miss", array($namespace));
            $result = $callback($this->db);
            $item->set($result, $this->expiration);
        }
        $this->stopTiming($namespace);
        return $result;
    }

    public function fetchAll($namespace, $sql) {
        $ns = implode('/', [$namespace, '*']);
        return $this->cachedQuery($ns, function ($db) use ($sql) {
            return $db->query($sql)->fetchAll();
        });
    }

    public function fetch($namespace, $id, $sql) {
        $ns = implode('/', [$namespace, $id]);
        return $this->cachedQuery($ns, function ($db) use ($sql, $id) {
            return $this->query($sql, [$id]);
        });
    }

    public function insert($sql, $params) {
        $sql .= ' RETURNING id ';
        return $this->query($sql, $params);
    }

    public function insertArray($table, $data) {
        if (count($data) == 0) {
            return false;
        }

        $names = array_keys($data);
        $sql  = "INSERT INTO {$table} (" . implode(', ', $names) . ") ";

        $placeholders = [];
        foreach ($names as $k) {
            $placeholders[":{$k}"] = $data[$k];
        }

        $sql .= " VALUES (" . implode(', ', array_keys($placeholders)) . ") ";
        return $this->insert($sql, $placeholders);
    }

    public function query($sql, $params = []) {
        $s = $this->db->prepare($sql);
        $s->execute($params);
        return $s->fetch();
    }

    public function uncachedFetchAll($sql, $params = []) {
        $s = $this->db->prepare($sql);
        $res = $s->execute($params);
        return $s->fetchAll();
    }

    public function flushAll($namespace) {
        $this->flush($namespace, '*');
    }

    public function flush($namespace, $id) {
        $ns = implode('/', [$namespace, $id]);
        $this->cache->getItem($ns)->clear();
    }
}
