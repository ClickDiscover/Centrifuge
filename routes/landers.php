<?php


$app->get('/content/:id', $app->container['route_middleware.view'], function ($id) use ($app, $centrifuge) {

    // $lander = $centrifuge['landers']->fetch($id);
    $lander = $app->environment['view']->lander;

    // if (!$lander) {
    //     $app->notFound();
    // }

    // View tracking
    // Old rotator did do bot tracking here if ($req->isBot()) {
    $tracking = $app->environment['tracking'];
    $context = $tracking['context'];

    // Campaign + Keyword Tracking
    $centrifuge['librato.performance']->total("views");
    $centrifuge['librato.performance']->breakout('lander', $lander->id, 'views');
    $keyword = $context->get('campaign', 'keyword', $app->request->params('keyword'));
    if (isset($keyword)) {
        $centrifuge['librato.performance']->breakout('keyword', $keyword, 'views');
    }
    $ad = $context->get('campaign', 'ad', $app->request->params('ad'));

    // User tracking
    // $_SESSION['last_lander'] = $lander;
    if (isset($tracking['cookie'])) {
        $tracking['cookie']->setLastVisitTime(time());
    }
    $pg = $centrifuge['segment']->landingPage($tracking, $lander);

    // Rendering
    $centrifuge['plates']->landerRender($app, $lander);

})->name('landers')->conditions(array(
    'id' => '[0-9]+'
));


$app->get('/landers/:id', function ($id) use ($app, $centrifuge) {
    // Slim's url for doesnt add querystring params really fucking annoying
    $url = $centrifuge['slim.urlFor']('landers', array('id' => $id));
    $app->redirect($url);
});
