<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
include CENTRIFUGE_MODELS_ROOT . "/lander.php";



$app->path('landers', function ($req) use ($app) {

    $app->param('int', function ($req, $id) use ($app)  {
        $lander = LanderFunctions::fetch($app, $id);
        $app->performance->total("views");
        $app->events->emit('foo', [1,2,3]);

        // View tracking
        if (ENABLE_LANDER_TRACKING) {
            $app->performance->breakout('lander', $lander->id, 'views');
            if ($req->isBot()) {
                $app->system->total('bot_hits');
                $app->log->warning("Bot Error", $_SERVER);
            }
            // $seg = array(
            //     "anonymousId" => str_replace('"', '', $_COOKIE['ajs_anonymous_id']),
            //     "name" => "Landing Page",
            //     "properties" => array(
            //         "url" => "/landers/" . $id
            //     )
            // );
            // echo '<pre>';
            // print_r($_COOKIE); echo '</pre>';
            // print_r($seg);
            // Segment::page($seg);
        }

        // Keyword Tracking
        $keyword = $req->query('keyword');
        if (isset($keyword)) {
            $app->performance->breakout('keyword', $keyword, 'views');
        }


        $app->get(function () use ($app, $lander)  {
            return $app->plates->render($lander->getTemplate(), $lander->toArray());
        });
    });
});


