<?php
if(php_sapi_name() === 'cli-server') {
    if (preg_match('/\/static\//', $_SERVER['REQUEST_URI'])) {
        return false;
    }
    if (strpos($_SERVER['PHP_SELF'], '/index.php') === false) {
        $_SERVER['PHP_SELF'] = '/index.php' . $_SERVER['PHP_SELF'];
        $_SERVER['SCRIPT_NAME'] = '/index.php';
    }
}

$rootDir = dirname(__DIR__);
require $rootDir . '/vendor/autoload.php';


$settings = require $rootDir . '/settings.php';
$container = new Slim\Container($settings);
$container->register(new ClickDiscover\CentrifugeServiceProvider());
$app = new Slim\App($container);

require_once $rootDir . '/routes/slim3.php';

$app->run();
?>
