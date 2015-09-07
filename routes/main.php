<?php


$app->get('/content/:id', $app->container['route_middleware.view'], function ($id) use ($app, $centrifuge) {

    $view = $app->environment['view'];
    $lander = $view->lander;

    $view->toLibrato($centrifuge['librato.performance']);
    $view->toSegment($centrifuge['segment']);

    $cookie = $view->getCookie();
    if (isset($cookie)) {
        $cookie->setLastVisitTime(time());
    }

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


$app->get('/click/:stepId', $app->container['route_middleware.click'], function ($stepId) use ($app, $centrifuge) {
    $req = $app->request;
    $click = $app->environment['click'];

    $click->toLibrato($centrifuge['librato.performance']);
    $click->toSegment($centrifuge['segment']);

    $cookie = $click->getCookie();
    if (isset($cookie)) {
        $cookie->setLastOfferClickTime(time());
    }

    // Now we redirect to cpv.flagshippromotions.com/base2.php
    // Eventually it will go to our campaign managment system
    $url = $app->config('click_url');
    $get = $app->request->get();
    $get['id'] = $stepId;
    $url .= "?" . http_build_query($get);
    $app->redirect($url);

})->name('click')->conditions(array('stepId' => '[0-9]+'));
