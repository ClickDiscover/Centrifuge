<?php

namespace Flagship\Service;

use Flagship\Model\Product;
require_once CENTRIFUGE_ROOT . '/src/Flagship/Util/adexchange.php';

class AdexOfferService {
    use \Flagship\Util\Logging;

    public $namespace = "ae_parameters";

    protected $imageUrlRoot = "http://www.img2srv.com/";
    protected $imageFileExt = ".png";

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
            $r = $this->curlFetch($p['affiliate_id'], $p['vertical'], $p['country']);
            return array(
                $this->makeProduct($r['step1_name'], $r['step1']),
                $this->makeProduct($r['step2_name'], $r['step2'])
            );
        }
    }

    protected function makeProduct($name, $imageId) {
        $url = $this->imageUrlRoot . $imageId . $this->imageFileExt;
        return new Product($name, $url);
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
            $this->log->info("Cache miss adexchange: ", array($affiliateId, $vertical, $country));
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
