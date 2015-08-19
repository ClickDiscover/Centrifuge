<?php

namespace Flagship\Service;


class NetworkOfferService {

    public $namespace = "product";

    protected $rootPath = '';
    protected $db;

    public function __construct($db, $pr = null) {
        $this->db = $db;
        $this->rootPath = $pr ? $pr : $this->rootPath;
    }

    public function fetch($id) {
        $sql = "SELECT id, name, image_url FROM products WHERE id = ?";
        $row = $this->db->fetch($this->namespace, $id, $sql);
        $url = $this->rootPath . $row['image_url'];
        return new \Flagship\Model\Product($row['name'], $url);
    }

    public function insert($name, $url) {
        $sql = "INSERT INTO products (name, image_url) VALUES (?, ?)";
        return $this->db->insert($sql, array($name, $url));
    }

}
