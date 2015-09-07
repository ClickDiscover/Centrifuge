<?php

namespace Flagship\Service;

use Flagship\Model\Product;
use Flagship\Model\AdexParameters;
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
            $r = $this->curlFetch($p);
            return array(
                $this->makeProduct($r['step1_name'], $r['step1'], $p->vertical),
                $this->makeProduct($r['step2_name'], $r['step2'], $p->vertical)
            );
        }
    }

    protected function makeProduct($name, $imageId, $vertical) {
        $url = $this->imageUrlRoot . $imageId . $this->imageFileExt;
        return new Product($imageId, $name, $url, 'adexchange', $vertical);
    }

    public function paramFetch($paramId) {
        $sql = "SELECT affiliate_id, vertical, country FROM ae_parameters WHERE id = ?";
        $res = $this->db->fetch($this->namespace, $paramId, $sql);
        $obj = new AdexParameters;
        $obj->fromArray($res);
        return $obj;
    }

    public function paramFetchAll() {
        $sql = "SELECT * FROM ae_parameters";
        $rows = $this->db->uncachedFetchAll($sql);
        $objects = [];
        foreach ($rows as $ae) {
            $obj = new AdexParameters;
            $obj->fromArray($ae);
            $obj->cleanName(); // Admin interfaceA
            $objects[] = $obj;
        }
        return $objects;
    }

    public function curlFetch(AdexParameters $p) {
        $pool = $this->curlCache;
        $item = $pool->getItem($this->namespace, $p->affiliateId, $p->vertical, $p->country);
        $result = $item->get();

        if ($item->isMiss()) {
            $this->log->info("Cache miss adexchange: ", array($p->affiliateId, $p->vertical, $p->country));
            // $app->system->total("ae_cache_miss");
            $result = ad_exchange_request($p->affiliateId, $p->vertical, $p->country);
            $item->set($result, $this->expires);
        }
        return $result;
    }

    public function insert($obj) {
        $arr = $obj->toArray();
        unset($arr['id']);
        $id = $this->db->insertArray($this->namespace, $arr);
        $arr['id'] = $id;
        $obj->fromArray($arr);
        return $obj;
    }

}
