<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
include CENTRIFUGE_MODELS_ROOT . "/lander.php";



$app->path('landers', function ($req) use ($app) {

    $app->param('int', function ($req, $id) use ($app)  {
        $lander = LanderFunctions::fetch($app, $id);

        $app->get(function () use ($app, $lander)  {
            return $app->plates->render($lander->getTemplate(), $lander->toArray());
        });
    });
});


