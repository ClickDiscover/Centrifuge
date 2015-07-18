<?php
if(php_sapi_name() === 'cli-server') {
    $filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if(is_file($filename)) {
        return false;
    }
}


require_once dirname(__DIR__) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
$app = new Bullet\App(require CENTRIFUGE_APP_ROOT . '/bullet.conf.php');
$request = new Bullet\Request();
require CENTRIFUGE_APP_ROOT . '/master.php';

echo "<pre>Request Timing</pre>";
$app->metrics->startTiming("request_time");

$app->path('ping', function ($req) use ($app) {
    return "pong!";
});
$app->path('test', function() use ($app) {
    $s = print_r($_SERVER, true);
    return "<pre>{$s}</pre>";
});




$routesDir = CENTRIFUGE_APP_ROOT . '/routes/';
require $routesDir . 'landers.php';
require $routesDir . 'admin.php';

echo $app->run($request);
$time = $app->metrics->endTiming("request_time");
echo "<pre>End timing {$time}</pre>";
