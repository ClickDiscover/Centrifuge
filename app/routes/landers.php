<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require BULLET_ROOT . '/vendor/autoload.php';
include BULLET_MODELS_ROOT . "/lander.php";

// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\RedirectResponse;


// use League\Url\Url;
// This should go in the master file
// $templates = new League\Plates\Engine(BULLET_ROOT . "/landers/");

$app->path('landers', function ($req) use ($app) {
    $db = new PDO(PDO_URL);

    $app->param('int', function ($req, $id) use ($app, $db)  {
        $lander = LanderFunctions::fetch($db, $id);

        $app->get(function () use ($app, $lander)  {
            return $app->plates->render($lander->template, $lander->toArray());
        });
    });
});


// $app->url('pure/garcinia', function ($req) use ($app) {
//     return $app->run('GET', 'landers/3')->content();
// });
