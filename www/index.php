<?php
if(php_sapi_name() === 'cli-server') {
    $filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if(is_file($filename)) {
        return false;
    }
}

require_once dirname(__DIR__) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
require CENTRIFUGE_APP_ROOT . '/util/slimplates.php';


$app = new Slim\Slim($config);
$app->setName(APPLICATION_NAME);
$app->view(PlatesView::fromConfig($config));



$app->get('/hello/:name', function ($name) use ($app) {
    $app->render('admin::models/test', array('name' => $name));
});


$app->run();
