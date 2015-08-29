<?php


$app->get('/content/:id', function ($id) use ($app, $centrifuge) {

    $lander = $centrifuge['landers']->fetch($id);

    if (!$lander) {
        $app->notFound();
    }

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

    // Segment.io tracking
    $pg = $centrifuge['segment']->landingPage($tracking, $lander);

    $template = $centrifuge['plates']->landerTemplate($lander);
    $app->render($template->getFile(), $template->getData());

})->name('landers')->conditions(array(
    'id' => '[0-9]+'
));


$app->get('/landers/:id', function ($id) use ($app) {
    $app->redirect($app->urlFor('landers', array('id' => $id)));
});
