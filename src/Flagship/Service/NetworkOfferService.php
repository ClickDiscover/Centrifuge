<?php

namespace Flagship\Service;

use \Flagship\Model\Product;

class NetworkOfferService {

    public $namespace = "product";

    protected $rootPath = '';
    protected $db;

    public function __construct($db, $pr = null) {
        $this->db = $db;
        $this->rootPath = $pr ? $pr : $this->rootPath;
    }

    public function fetch($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $row = $this->db->fetch($this->namespace, $id, $sql);
        return $this->fromRow($row);
    }

    protected function fromRow($row) {
        $url = $this->rootPath . $row['image_url'];
        $vert = isset($row['vertical']) ? $row['vertical'] : 'NONE';
        return new Product(
            $row['id'],
            $row['name'],
            $url,
            $row['source'],
            $vert
        );
    }

    public function insert($name, $url) {
        $sql = "INSERT INTO products (name, image_url) VALUES (?, ?)";
        return $this->db->insert($sql, array($name, $url));
    }

    public function fetchAll() {
        $sql = "SELECT * FROM products";
        $rows = $this->db->uncachedFetchAll($sql);
        return array_map(function ($x) {
            return $this->fromRow($x);
        }, $rows);
    }
}
