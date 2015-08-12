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

$app->metrics->startTiming("-centrifuge.request_time");



$routesDir = CENTRIFUGE_APP_ROOT . '/routes/';
require $routesDir . 'landers.php';
require $routesDir . 'clicks.php';
require $routesDir . 'admin.php';
require $routesDir . 'conversions.php';

echo $app->run($request);
$time = $app->metrics->endTiming("-centrifuge.request_time");
