<?php

namespace Flagship\Service;


class AdexOfferService {

    public $namespace = "ae_parameters";

    protected $db;
    protected $curlCache;

    public function __construct($db, $curlCache) {
        $this->db = $db;
        $this->curlCache = $curlCache;
    }

    public function fetch($id) {
        $sql = "SELECT id, name, image_url FROM products WHERE id = ?";
        $row = $db->fetch($this->namespace, $id, $sql));
    }

    public function paramFetch($paramId) {
        $sql = "SELECT affiliate_id, vertical, country FROM ae_parameters WHERE id = ?";
        return $db->fetch($this->adExchangeNamespace, $paramId, $sql);
    }
}
