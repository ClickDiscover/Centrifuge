<?php

require 'vendor/autoload.php';
use League\Url\Url;
use League\Url\UrlImmutable;

function click_url($step_id)
{
    $url = Url::createFromServer($_SERVER);
    $url->setPath("base2.php");
    $url->getQuery()->modify(array("id" => $step_id));
    return $url;
}


$affiliate_id = 170317;
$vertical = "skin";
$country = "US";

$step1_link = "http://cpv.flagshippromotions.com/base2.php?id=1";

if (isset($_SERVER['QUERY_STRING'])) {
    $step1_link .= '&'.$_SERVER['QUERY_STRING'];
}
// &" . $_SERVER['QUERY_STRING'];
$step1_name = "NuVie Firming Serum";
$step1_image = "http://www.img2srv.com/78.png";

$step2_link = "http://cpv.flagshippromotions.com/base2.php?id=2";
if (isset($_SERVER['QUERY_STRING'])) {
    $step2_link .= '&'.$_SERVER['QUERY_STRING'];
}

$step2_name = "Bright Skin Cream";
$step2_image = "http://www.img2srv.com/272.png";
