<?php

require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
require_once CENTRIFUGE_APP_ROOT . '/util/adexchange.php';
require_once CENTRIFUGE_APP_ROOT . '/util/identify.php';
use League\Url\Url;


interface Product
{
    public function getName();
    public function getImageUrl();
}

class AdExchangeProduct implements Product
{
    protected $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function fetchFromAdExchange($app, $affiliate_id, $vert, $country)
    {
        $pool = $app->cache;
        $item = $pool->getItem('adexchange', $affiliate_id, $vert, $country);
        $result = $item->get();

        if ($item->isMiss()) {
            $app->log->info("Cache miss adexchange: ", array($affiliate_id, $vert, $country));
            $app->metrics->increment("centrifuge.ae_cache_miss");
            $result = ad_exchange_request($affiliate_id, $vert, $country, $_SERVER["HTTP_USER_AGENT"]);
            $item->set($result, AE_TTL);
        }

        $s1 = new AdExchangeProduct($result['step1'], $result['step1_name']);
        $s2 = new AdExchangeProduct($result['step2'], $result['step2_name']);
        return array($s1, $s2);
    }

    public function getName() {
        return $this->name;
    }

    public function getImageUrl() {
        return "http://www.img2srv.com/".$this->id.".png";
    }

    public static function insert($app, $arr) {
        $sql = "INSERT INTO ae_parameters (affiliate_id, vertical, country, name) VALUES (:affiliate_id, :vertical, :country, :name)";
        return $app->db()->prepare($sql)->execute($arr);
    }
}

class NetworkProduct implements Product
{
    use Identifiable;
    protected $name;
    protected $imageUrl;

    public function __construct($id, $name, $imageUrl, $productRoot = null) {
        $this->id = $id;
        $this->name = $name;
        if(!is_null($productRoot)) {
            $imageUrl = $productRoot . $imageUrl;
        }
        $this->imageUrl = $imageUrl;
    }

    public function getName() {
        return $this->name;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }

    public static function fromArray($arr, $productRoot = null) {
        return new NetworkProduct($arr['id'], $arr['name'], $arr['image_url'], $productRoot);
    }

    public static function insert($app, $name, $imageUrl) {
        $stmt = $app->db()->prepare("INSERT INTO products (name, image_url) VALUES (?, ?)");
        return $stmt->execute(array($name, $imageUrl));
    }
}
