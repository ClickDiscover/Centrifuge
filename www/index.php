<?php
if(php_sapi_name() === 'cli-server') {
    $filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if(is_file($filename)) {
        return false;
    }
}

require_once dirname(__DIR__) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
require CENTRIFUGE_APP_ROOT . '/centrifuge.php';

$centrifuge =  new Centrifuge($config);
$app = new Slim\Slim($config);
$app->setName(APPLICATION_NAME);
$centrifuge->instrumentSlim($app);




$app->get('/hello/:name', function ($name) use ($app) {
    $app->log->info("Testing");
    $sites = $app->container['db']->query("SELECT distinct namespace from websites")->fetchAll(PDO::FETCH_ASSOC);
    $app->render('admin::models/test', array('sites' => $sites, 'name' => $name));
});


$app->run();
