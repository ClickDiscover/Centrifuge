<?php

namespace Flagship\Service;


class CustomRouteService {

    protected $namespace = 'routes';
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetchAll() {
        $sql = "SELECT * FROM routes";
        return $this->db->fetchAll($this->namespace, $sql);
    }

    public function fetchForLander($id) {
        $sql = "SELECT * FROM routes WHERE lander_id = ?";
        return $this->db->fetch($this->namespace, $id, $sql);
    }

    public function insert($url, $landerId) {
        $sql = "INSERT INTO routes (url, lander_id) VALUES (?, ?)";
        return $this->db->insert($sql, array($url, $landerId));
    }
}
