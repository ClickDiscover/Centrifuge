<?php

require_once dirname(dirname(__DIR__)) . '/config.php';
require BULLET_ROOT . '/vendor/autoload.php';
require_once BULLET_APP_ROOT . '/util/adexchange.php';
use League\Url\Url;


interface Product
{
    public function getName();
    public function getImageUrl();
}

class AdExchangeProduct implements Product
{
    protected $id;
    protected $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function fetchFromAdExchange($affiliate_id, $vert, $country)
    {
        $res = ad_exchange_request($affiliate_id, $vert, $country, $_SERVER["HTTP_USER_AGENT"]);
        $s1 = new AdExchangeProduct($res['step1'], $res['step1_name']);
        $s2 = new AdExchangeProduct($res['step2'], $res['step2_name']);
        return array($s1, $s2);
    }

    public function getName() {
        return $this->name;
    }

    public function getImageUrl() {
        return "http://www.img2srv.com/".$this->id.".png";
    }
}

class NetworkProduct implements Product
{
    protected $name;
    protected $imageUrl;

    public function __construct($name, $imageUrl, $productRoot = null) {
        $this->name = $name;
        if(!is_null($productRoot)) {
            $imageUrl = $productRoot . $imageUrl;
        }
        $this->imageUrl = $imageUrl;
    }

    public static function fromArray($arr, $productRoot = null) {
        return new NetworkProduct($arr['name'], $arr['image_url'], $productRoot);
    }

    public function getName() {
        return $this->name;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }
}
