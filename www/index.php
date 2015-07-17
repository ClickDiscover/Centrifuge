<?php
if(php_sapi_name() === 'cli-server') {
    $filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if(is_file($filename)) {
        return false;
    }
}


require_once dirname(__DIR__) . '/config.php';
require BULLET_ROOT . '/vendor/autoload.php';
$app = new Bullet\App(require BULLET_APP_ROOT . 'bullet.conf.php');
$request = new Bullet\Request();


$app->path('ping', function ($req) use ($app) {
    return "pong!";
});
$app->path('test', function() use ($app) {
    $s = print_r($_SERVER, true);
    return "<pre>{$s}</pre>";
});

$app->path('info', function() use ($app) {
    return phpinfo();
});



require BULLET_APP_ROOT . '/master.php';
$routesDir = BULLET_APP_ROOT . '/routes/';
require $routesDir . 'landers.php';
// require $routesDir . 'events.php';


echo $app->run($request);
