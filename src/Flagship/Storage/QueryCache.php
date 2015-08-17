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
        return $this->cachedQuery($namespace, function ($db) use ($sql) {
            return $db->query($sql)->fetchAll();
        });
    }

    public function fetch($namespace, $id, $sql) {
        return $this->cachedQuery($namespace, function ($db) use ($sql, $id) {
            $s = $db->prepare($sql);
            $s->execute(array($id));
            return $s->fetch();
        });
    }
}
