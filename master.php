<?php

require_once "models/product.php";
require_once "models/step.php";
require_once "models/tracking.php";
require 'vendor/autoload.php';

$templates    = new League\Plates\Engine(__DIR__.'/templates/');
$affiliate_id = 170317;
$vertical     = "skin";
$country      = "US";
$res          = Product::fetchFromAdExchange($affiliate_id, $vertical, $country);
$steps        = Step::fromProducts($res);

$tracking = new Tracking();

echo $templates->render('foo', ['steps' => $steps, 'tracking' => $tracking]);
