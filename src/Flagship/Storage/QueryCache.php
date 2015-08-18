<?php

namespace Flagship\Storage;

use Flagship\Container;


class QueryCache {
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
            return $this->prepared($sql, [$id]);
        });
    }

    public function insert($sql, $params) {
        $sql .= ' RETURNING id ';
        return $this->prepared($sql, $params);
    }

    public function prepared($sql, $params = []) {
        $s = $this->db->prepare($sql);
        $s->execute($params);
        return $s->fetch();
    }
}
