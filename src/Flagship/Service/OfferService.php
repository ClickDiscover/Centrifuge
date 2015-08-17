<?php

namespace Flagship\Service;


class OfferService {

    public $namespace = "product";
    public $adexNamespace = "ae_parameters";

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetch($id) {
        $sql = "SELECT id, name, image_url FROM products WHERE id = ?";
        $row = $db->fetch($this->namespace, $id, $sql));
    }

    public function adexParamFetch($paramId) {
        $sql = "SELECT affiliate_id, vertical, country FROM ae_parameters WHERE id = ?";
        return $db->fetch($this->adExchangeNamespace, $paramId, $sql);
    }
}
