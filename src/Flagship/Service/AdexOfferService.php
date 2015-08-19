<?php

namespace Flagship\Service;

require_once CENTRIFUGE_ROOT . '/src/Flagship/util/adexchange.php';

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
    public function fetch($id) {
        $p = $this->paramFetch($id);
        if ($p) {
            return $this->curlFetch($p['affiliate_id'], $p['vertical'], $p['country']);
        }
    }

    public function paramFetch($paramId) {
        $sql = "SELECT affiliate_id, vertical, country FROM ae_parameters WHERE id = ?";
        return $this->db->fetch($this->namespace, $paramId, $sql);
    }

    public function curlFetch($affiliateId, $vertical, $country) {
        $pool = $this->curlCache;
        $item = $pool->getItem($this->namespace, $affiliateId, $vertical, $country);
        $result = $item->get();

        if ($item->isMiss()) {
            // $app->log->info("Cache miss adexchange: ", array($affiliate_id, $vert, $country));
            // $app->system->total("ae_cache_miss");
            $result = ad_exchange_request($affiliateId, $vertical, $country);
            $item->set($result, $this->expires);
        }

        // $s1 = new AdExchangeProduct($result['step1'], $result['step1_name']);
        // $s2 = new AdExchangeProduct($result['step2'], $result['step2_name']);
        return $result;
    }

    public function insert($app, $arr) {
        $sql = "INSERT INTO ae_parameters (affiliate_id, vertical, country, name) VALUES (:affiliate_id, :vertical, :country, :name)";
        return $db->insert($sql, $arr);
    }

}
