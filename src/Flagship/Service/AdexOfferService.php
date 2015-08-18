<?php

namespace Flagship\Service;

require_once CENTRIFUGE_ROOT . '/src/util/adexchange.php';

class AdexOfferService {

    public $namespace = "ae_parameters";

    protected $db;
    protected $curlCache;
    protected $expires;

    public function __construct($db, $curlCache, $expires) {
        $this->db = $db;
        $this->curlCache = $curlCache;
        $this->expiration = $expires;
    }

    public function paramFetch($paramId) {
        $sql = "SELECT affiliate_id, vertical, country FROM ae_parameters WHERE id = ?";
        return $db->fetch($this->adExchangeNamespace, $paramId, $sql);
    }

    public static function insert($app, $arr) {
        $sql = "INSERT INTO ae_parameters (affiliate_id, vertical, country, name) VALUES (:affiliate_id, :vertical, :country, :name)";
        return $db->insert($sql, $arr);
    }

    public function fetch($id) {
        $sql = "SELECT id, name, image_url FROM products WHERE id = ?";
        $row = $db->fetch($this->namespace, $id, $sql));
    }

    public function fetch($affiliateId, $vertical, $country) {
    {
        $pool = $this->curlCache;
        $item = $pool->getItem($this->namespace, $affiliateId, $vertical, $country);
        $result = $item->get();

        if ($item->isMiss()) {
            // $app->log->info("Cache miss adexchange: ", array($affiliate_id, $vert, $country));
            // $app->system->total("ae_cache_miss");
            $result = ad_exchange_request($affiliate_id, $vert, $country);
            $item->set($result, $this->expires);
        }

        // $s1 = new AdExchangeProduct($result['step1'], $result['step1_name']);
        // $s2 = new AdExchangeProduct($result['step2'], $result['step2_name']);
        return array($s1, $s2);
    }

}
