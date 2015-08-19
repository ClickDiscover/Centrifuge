<?php

namespace Flagship\Storage;

use Flagship\Container;


class QueryCache {
    use \Flagship\Util\Logging;

    protected $db;
    protected $cache;
    protected $expiration;

    public function __construct($db, $cache, $expiration) {
        $this->db = $db;
        $this->cache = $cache;
        $this->expiration = $expiration;
    }

    public function cachedQuery($namespace, $callback) {
        $item = $this->cache->getItem($namespace);
        $result = $item->get();
        if($item->isMiss()) {
            $this->log->info("Cache miss", array($namespace));
            $result = $callback($this->db);
            $item->set($result, $this->expiration);
        }
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

    public function query($sql, $params = []) {
        $s = $this->db->prepare($sql);
        $s->execute($params);
        return $s->fetch();
    }

    public function uncachedFetchAll($sql, $params = []) {
        $s = $this->db->query($sql);
        $s->execute($params);
        return $s->fetchAll();
    }
}
