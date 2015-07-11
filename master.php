<?php

require 'vendor/autoload.php';
use League\Url\Url;


function click_url($step_id)
{
    $url = Url::createFromServer($_SERVER);
    $url->setPath("base2.php");
    $url->getQuery()->modify(array("id" => $step_id));
    return $url;
}
