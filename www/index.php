<?php
if(php_sapi_name() === 'cli-server') {
    // if(preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER['REQUEST_URI'])) {
    if (preg_match('/\/static\//', $_SERVER['REQUEST_URI'])) {
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

// $app->hook('slim.after', function () use ($app) {
//     echo "After";
//     $app->log->info("We are after");
// });

$app->get('/hello/:name', function ($name) use ($app, $centrifuge) {
    // $sites = $app->container['db']->fetchAll("distinct/websites", "SELECT distinct namespace from websites");
    $sites = $centrifuge['landers']->fetch($name);
    $app->render('admin::models/test', array('sites' => $sites, 'name' => $name));
});


require_once $config['paths']['routes'] . 'landers.php';
require_once $config['paths']['routes'] . 'clicks.php';
require_once $config['paths']['routes'] . 'conversions.php';

$app->run();
?>
