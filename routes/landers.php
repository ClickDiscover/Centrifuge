<?php


$app->get('/content/:id', function ($id) use ($app, $centrifuge) {

    $lander = $centrifuge['landers']->fetch($id);

    if (!$lander) {
        $app->notFound();
    }


    // View tracking
    $centrifuge['librato.performance']->total("views");
    $centrifuge['librato.performance']->breakout('lander', $lander->id, 'views');
    $_SESSION['lander_count'] = isset($_SESSION['lander_count']) ? 1 + $_SESSION['lander_count'] : 0;

    // Not sure if I care about this as a flag
    // $enabled = $app->config('flags');
    // if ($enabled['lander_tracking']) {
        // if ($req->isBot()) {
        //     $app->system->total('bot_hits');
        //     $app->log->warning("Bot Error", $_SERVER);
        // }
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
    // }

    // Keyword Tracking
    $keyword = $app->request->get('keyword');
    if (isset($keyword)) {
        $centrifuge['librato.performance']->breakout('keyword', $keyword, 'views');
    }

    $template = $centrifuge['plates']->landerTemplate($lander);
    $app->render($template->getFile(), $template->getData());

})->name('landers')->conditions(array(
    'id' => '[0-9]+'
));


$app->get('/landers/:id', function ($id) use ($app) {
    $app->redirect($app->urlFor('landers', array('id' => $id)));
});
