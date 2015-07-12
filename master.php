<?php

include_once "adexchange.php";
require_once "models/product.php";
require_once "models/step.php";
require 'vendor/autoload.php';

$templates    = new League\Plates\Engine(__DIR__.'/templates/');
$affiliate_id = 170317;
$vertical     = "skin";
$country      = "US";
$res          = Product::fetchFromAdExchange($affiliate_id, $vertical, $country);

$step_count = 1;
$steps = array();
foreach ($res as $r) {
    $steps[$step_count] = new Step($step_count, $r);
    $step_count++;
}

echo $templates->render('foo', ['steps' => $steps]);
