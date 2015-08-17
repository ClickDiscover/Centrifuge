<?php

namespace Flagship\Service;


class NetworkOfferService {

    public $namespace = "product";

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetch($id) {
        $sql = "SELECT id, name, image_url FROM products WHERE id = ?";
        $row = $db->fetch($this->namespace, $id, $sql));
    }

}
