<?php

require 'vendor/autoload.php';
require_once 'adexchange.php';
use League\Url\Url;


class Product
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
        $s1 = new Product($res['step1'], $res['step1_name']);
        $s2 = new Product($res['step2'], $res['step2_name']);
        return array($s1, $s2);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getImageUrl()
    {
        return "http://www.img2srv.com/".$this->id.".png";
    }
}
