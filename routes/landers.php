<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
include CENTRIFUGE_SRC_ROOT . "/models/lander.php";


$app->get('/landers/:id', function ($id) use ($app) {

    echo "Lander " . $id;
    $lander = LanderFunctions::fetch($app, $id);
    var_dump($lander);
    // $app->render($lander->getTemplate(), $lander->toArray());

    // View tracking
    // $app->performance->total("views");
    //
    // $enabled = $app->config('flags');
    // if ($enabled['lander_tracking']) {
    //     $app->performance->breakout('lander', $lander->id, 'views');
    //     if ($req->isBot()) {
    //         $app->system->total('bot_hits');
    //         $app->log->warning("Bot Error", $_SERVER);
    //     }
    //     $seg = array(
    //         "anonymousId" => str_replace('"', '', $_COOKIE['ajs_anonymous_id']),
    //         "name" => "Landing Page",
    //         "properties" => array(
    //             "url" => "/landers/" . $id
    //         )
    //     );
    //     echo '<pre>';
    //     print_r($_COOKIE); echo '</pre>';
    //     print_r($seg);
    //     Segment::page($seg);
    // }

    // Keyword Tracking
    // $keyword = $req->query('keyword');
    // if (isset($keyword)) {
    //     $app->performance->breakout('keyword', $keyword, 'views');
    // }



})->conditions(array(
    'id' => '[0-9]+'
));


