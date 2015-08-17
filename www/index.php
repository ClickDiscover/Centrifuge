<?php
if(php_sapi_name() === 'cli-server') {
    if(preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER['REQUEST_URI'])) {
        return false;
    }
    if (strpos($_SERVER['PHP_SELF'], '/index.php') === false) {
        $_SERVER['PHP_SELF'] = '/index.php' . $_SERVER['PHP_SELF'];
        $_SERVER['SCRIPT_NAME'] = '/index.php';
    }
}

require_once dirname(__DIR__) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';

$centrifuge =  new Flagship\Container($config);
$app = new Slim\Slim($config['application']);
$app->setName($config['name']);
$bootstrap = new Flagship\SlimBootstrap($app, $centrifuge);
$app = $bootstrap->bootstrap();


$app->hook('slim.before', function () use ($app) {
    echo "Before";
    $app->log->info("Here");
});


$app->get('/hello/:name', function ($name) use ($app, $config) {
    $sites = $app->container['db']->fetchAll("distinct/websites", "SELECT distinct namespace from websites");
    $app->render('admin::models/test', array('sites' => $sites, 'name' => $name));
});


// require_once $config['paths']['routes'] . 'landers.php';
require_once $config['paths']['routes'] . 'clicks.php';
require_once $config['paths']['routes'] . 'conversions.php';

$app->run();
?>
