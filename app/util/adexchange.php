<?php

require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
use League\Url\Url;

function ad_exchange_request($affiliate_id, $vertical, $country, $user_agent)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,"http://hits2sales.com/api/current_campaigns.php");
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array(
        "affiliate_id" => $affiliate_id,
        "country" => $country,
        "vertical" => $vertical
    )));
    curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    if(isset($user_agent))
    {
        curl_setopt($ch,CURLOPT_USERAGENT, $user_agent);
    }
    $return = curl_exec($ch);
    return json_decode($return, true);
}


function click_url($step_id)
{
    $url = Url::createFromServer($_SERVER);
    $url->setPath("base2.php");
    $url->getQuery()->modify(array("id" => $step_id));
    return $url;
}


// function ad_exchange_url($affiliate_id, $vertical, $country)
// {
//     $res = ad_exchange_request($affiliate_id, $vertical, $country, $_SERVER["HTTP_USER_AGENT"]);
//     return $res;
// }
