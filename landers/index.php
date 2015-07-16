<?php
require_once dirname(__DIR__) . '/config.php';
$loader = require BULLET_ROOT . '/vendor/autoload.php';


$app = new Bullet\App(require BULLET_APP_ROOT . 'bullet.conf.php');
$request = new Bullet\Request();


$app->path('/', function ($req) use ($app) {
    return "Hello!";
});



// require BULLET_APP_ROOT . '/master.php';
// $routesDir = BULLET_APP_ROOT . '/routes/';
// require $routesDir . 'index.php';
// require $routesDir . 'events.php';
// require $routesDir . 'messages.php';
// require $routesDir . 'users.php';

// if($request->isCli()) {
//     require $routesDir . 'db.php';
// }

echo $app->run($request);
