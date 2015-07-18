<?php
require_once dirname(__DIR__) . '/config.php';

$log = new Monolog\Logger('centrifuge');
$log->pushHandler(new Monolog\Handler\StreamHandler(CENTRIFUGE_LOG_ROOT, CENTRIFUGE_LOG_LEVEL));


$db = new PDO(PDO_URL);


$cacheDriver = new Stash\Driver\FileSystem();
$cacheDriver->setOptions(array('path' => CENTRIFUGE_CACHE_ROOT));
$cache = new Stash\Pool($cacheDriver);

$connection = new \Domnikl\Statsd\Connection\UdpSocket('localhost', 8125);
$metrics = new \Domnikl\Statsd\Client($connection, 'statsd.centrifuge');
