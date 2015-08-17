<?php
require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/util/librato.php';

// use Symfony\Component\HttpFoundation\Session\Session;
// use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

use Stash\Session;


$log = new Monolog\Logger('centrifuge');
$log->pushHandler(new Monolog\Handler\StreamHandler(
    CENTRIFUGE_LOG_ROOT . 'centrifuge.log',
    CENTRIFUGE_LOG_LEVEL
));
$log->pushProcessor(new Monolog\Processor\WebProcessor);
$log->pushProcessor(new Monolog\Processor\MemoryUsageProcessor);


// $db = function () {
//     return new PDO(PDO_URL);
// };
$db = new F3\LazyPDO\LazyPDO(PDO_URL);


$cacheDriver = new Stash\Driver\FileSystem();
$cacheDriver->setOptions(array('path' => CENTRIFUGE_CACHE_ROOT));
$cache = new Stash\Pool($cacheDriver);
$cache->setNamespace('centrifuge');
$cache->setLogger($log);
$sessionCache= new Stash\Pool($cacheDriver);
$sessionCache->setNamespace('session');
$sessionCache->setLogger($log);
Session::registerHandler(new Session($sessionCache));




$connection = new \Domnikl\Statsd\Connection\UdpSocket('localhost', 8125);
$source = LIBRATO_ENV;
$metrics = new \Domnikl\Statsd\Client($connection);
$performanceMetrics = new LibratoMetrics($metrics, [LIBRATO_ENV], ['centrifuge', 'performance']);
$systemMetrics = new LibratoMetrics($metrics, [LIBRATO_ENV, HOSTNAME], ['centrifuge', 'system']);

session_start();
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
} else {
    $_SESSION['count']++;
}
// setcookie("FP_SessionId", session_id());
Segment::init(SEGMENT_KEY);
