<?php
require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/util/librato.php';

$log = new Monolog\Logger('centrifuge');
$log->pushHandler(new Monolog\Handler\StreamHandler(CENTRIFUGE_LOG_ROOT, CENTRIFUGE_LOG_LEVEL));


// $db = new PDO(PDO_URL);

$db = function () {
    return new PDO(PDO_URL);
};


$cacheDriver = new Stash\Driver\FileSystem();
$cacheDriver->setOptions(array('path' => CENTRIFUGE_CACHE_ROOT));
$cache = new Stash\Pool($cacheDriver);

$connection = new \Domnikl\Statsd\Connection\UdpSocket('localhost', 8125);
// $source = HOSTNAME . '.' . LIBRATO_ENV;
$source = LIBRATO_ENV;
$metrics = new \Domnikl\Statsd\Client($connection, $source);
$performanceMetrics = new LibratoMetrics($metrics, [LIBRATO_ENV], ['centrifuge', 'performance']);
$systemMetrics = new LibratoMetrics($metrics, [LIBRATO_ENV, HOSTNAME], ['centrifuge', 'system']);

