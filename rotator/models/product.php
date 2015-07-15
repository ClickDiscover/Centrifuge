<?php

require dirname(__FILE__).'/../vendor/autoload.php';
require_once dirname(__FILE__).'/../util/adexchange.php';
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

    public function __construct($name, $imageUrl) {
        $this->name = $name;
        $this->imageUrl = $imageUrl;
    }

    public static function fromArray($arr) {
        return new NetworkProduct($arr['name'], $arr['image_url']);
    }

    public function getName() {
        return $this->name;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }
}
