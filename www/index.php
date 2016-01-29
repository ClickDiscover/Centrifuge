<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

$rootDir = dirname(__DIR__);
require $rootDir . '/vendor/autoload.php';


$settings = require $rootDir . '/settings.php';
$container = new Slim\Container($settings);
$centrifuge = new ClickDiscover\CentrifugeServiceProvider();
$centrifuge->register($container);
$app = new Slim\App($container);

$app->get('/settings', function ($req, $res, $args) use ($app) {
    echo '<pre>';
    print_r($app->getContainer());
    echo '</pre>';
});

$app->get('/content/{id:[0-9]+}', function ($req, $res, $args) {
    $lander = $this->landers->fetch($args['id']);
    echo '<pre>';
    echo "Welcome to " . $args['id'] . PHP_EOL;
    // echo $cs() . PHP_EOL;
    print_r($lander);
    // echo $cs() . PHP_EOL;
    echo '</pre>';
});

$app->run();
?>
