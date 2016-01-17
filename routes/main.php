<?php

use Flagship\Event\Click;
use Flagship\Event\View;

$app->get('/content/:id', function ($id) use ($app, $centrifuge) {
    $lander = $centrifuge['landers']->fetch($id);
    if (!$lander) {
        $app->notFound();
    }

    // $view = $app
        // ->environment['event.builder']
        // ->setLander($lander)
        // ->buildView();
    // $view->track($centrifuge);

    $centrifuge['plates']->landerRender($app, $lander);
    $_SESSION['last_lander'] = $lander;

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
    $lander = $app->environment['referring.lander'];
    // $click = $app
        // ->environment['event.builder']
        // ->setLander($lander)
        // ->setStepId($stepId)
        // ->buildClick();

    // $click->track($centrifuge);

    // Now we redirect to cpv.flagshippromotions.com/base2.php
    // Eventually it will go to our campaign managment system
    $url = $app->config('click_url');
    $stepName = $app->config('click_step_name');
    $get = $app->request->get();
    $get[$stepName] = $stepId;
    $url .= "?" . http_build_query($get);

    // app::redirect halts the call stack so pending hooks don't fire
    $app->response->redirect($url);

})->name('click')->conditions(array('stepId' => '[0-9]+'));
