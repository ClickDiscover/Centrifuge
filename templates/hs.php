<?php

require_once dirname(__FILE__)."/../models/product.php";
require_once dirname(__FILE__)."/../models/step.php";

$affiliate_id = 170317;
$vertical     = "skin";
$country      = "US";
$res          = Product::fetchFromAdExchange($affiliate_id, $vertical, $country);
$steps        = Step::fromProducts($res);
