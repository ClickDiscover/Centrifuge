<?php

require 'vendor/autoload.php';

$settings = require_once 'settings.php';
// $container = new \Slim\Container($settings);
// $services  = new \ClickDiscover\Centrifuge\ServiceProvider();
// $services->register($container);
//
$container = \ClickDiscover\Centrifuge\ServiceProvider::withSlimContainer($settings);

$faker = Faker\Factory::create();

$NETWORKS = 3;
$PRODUCTS = 10;

$OFFERS = 10;

$TEXTS = 3;
$IMAGES = 3;
$CTA = 3;

$ARTICLES = 5;

$SLOTS = 3; 

function output($t, $cols, $f) {
    global $container;
    $sql = 'INSERT INTO ' . $t . ' (' . implode(',', $cols) . ') VALUES (' . implode(',', array_pad([], count($cols), '?')) . ')';
    echo $sql . PHP_EOL;
    print_r($f);
    $s = $container['pdo']->prepare($sql);
    $res = $s->execute($f);
    var_dump($s);
    echo $res;
}

// foreach (range(0, $SLOTS) as $i) {
    // $f = [$faker->uuid, 1, 1,  true];
    // output('slots', ['uuid', 'article_id', 'type', 'enabled'], $f);
// }


// foreach (range(1, 4) as $x) {
    // $f = [$faker->uuid, 11, $x];
    // output('traffickings', ['uuid', 'offer_id', 'slot_id'], $f);
// }

output('offers_creatives', ['uuid', 'offer_id', 'slot_id'], [$faker->uuid, 3, 1]);
output('offers_creatives', ['uuid', 'offer_id', 'slot_id'], [$faker->uuid, 11, 2]);

// foreach (range(1, 4) as $x) {
    // foreach (range(1, 4) as $y) {
        // foreach (range(1,4) as $z) {
            // $f = [$faker->uuid, $x, $y, $z];
            // output('creatives', ['uuid', 'text_id', 'image_id', 'cta_id'], $f);
        // }
    // }
// }

// echo 'networks'.PHP_EOL;
// foreach (range(0, $NETWORKS) as $i) {
    // $f = [$faker->uuid, $faker->company];
    // output('networks', ['uuid', 'name'], $f);
// }

// echo 'PRODUCTS'.PHP_EOL;
// foreach (range(0, $PRODUCTS) as $i) {
    // $f = [$faker->uuid, $faker->word, $faker->image($dir = 'www/images')];
    // output('products', ['uuid', 'name', 'image_href'], $f);
// }

// echo 'OFFERS'.PHP_EOL;
// foreach (range(0, $OFFERS) as $i) {
    // $f = [$faker->uuid, 'cpa', rand(0, $PRODUCTS), rand(0, $NETWORKS)];
    // output('offers', ['uuid', 'incentive', 'product_id', 'network_id'], $f);
// }

// echo 'TEXTS'.PHP_EOL;
// foreach (range(0, $TEXTS) as $i) {
    // $f = [$faker->uuid, $faker->catchPhrase];
    // output('ad_text', ['uuid', 'text'], $f);
// }


// echo 'IMAGES'.PHP_EOL;
// foreach (range(0, $IMAGES) as $i) {
    // $f = [$faker->uuid, $faker->image($dir='www/images')];
    // output('ad_image', ['uuid', 'image'], $f);
// }


// echo 'CTA'.PHP_EOL;
// foreach (range(0, $CTA) as $i) {
    // $f = [$faker->uuid, $faker->word, $faker->hexcolor, '<a>'];
    // output('ad_cta', ['uuid', 'text', 'color', 'element'], $f);
// }
