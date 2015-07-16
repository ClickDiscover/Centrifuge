<?php

require 'vendor/autoload.php';


$app = new Bullet\App();
$app->path('/', function($request) {
    return "Hello World!";
});

echo $app->run(new Bullet\Request());

