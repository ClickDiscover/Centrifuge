<?php


$app->get('/content/:id', function ($id) use ($app, $centrifuge) {

    $lander = $centrifuge['landers']->fetch($id);
    $template = $centrifuge['plates']->landerTemplate($lander);
    $app->render($template->getFile(), $template->getData());

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



})->name('landers')->conditions(array(
    'id' => '[0-9]+'
));


