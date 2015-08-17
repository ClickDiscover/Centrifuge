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
require CENTRIFUGE_APP_ROOT . '/centrifuge.php';

$centrifuge =  new SlimBootstrap($config);
$app = new Slim\Slim($config);
$app->setName($config['name']);
// $centrifuge->instrumentSlim($app);

$app->get('/hello/:name', function ($name) use ($app) {
    $sites = $app->container['db']->query("SELECT distinct namespace from websites")->fetchAll();
    $app->render('admin::models/test', array('sites' => $sites, 'name' => $name));
});

require_once $config['paths']['routes'] . 'landers.php';
require_once $config['paths']['routes'] . 'clicks.php';
require_once $config['paths']['routes'] . 'conversions.php';

?><pre><?php
$app->run();
?></pre>
