<?php
use League\Url\Url;




$app->get('/click/:stepId', $app->container['route_middleware.click'], function ($stepId) use ($app, $centrifuge) {
    $req = $app->request;


    // Cookie Tracking
    $tracking = $app->environment['tracking'];
    $tracking['click.step_id'] = $stepId;
    if (isset($tracking['cookie'])) {
        $tracking['cookie']->setLastOfferClickTime(time());
    }

    $click = $app->environment['click'];
    $click->toLibrato($centrifuge['librato.performance']);

    // Lander Tracking
    // $centrifuge['librato.performance']->total("clicks");
    // $lander = landerFromRequest($centrifuge['landers'], $req);
    // $lander = $app->environment['click']->lander;
    // if (isset($lander)) {
        // $centrifuge['logger']->info('Lander', [$lander->id]);
    //     $centrifuge['librato.performance']->breakout('lander', $lander->id, 'clicks');
    //     $centrifuge['segment']->offerClick($tracking, $lander);
    // }

    // Keyword Tracking
    // $keyword = $req->get('keyword');
    // if (isset($keyword)) {
    //     $centrifuge['librato.performance']->breakout('keyword', $keyword, 'clicks');
    // }

    // Now we redirect to cpv.flagshippromotions.com/base2.php
    // Eventually it will go to our campaign managment system
    $url = null;
    $clickMethod = $app->config('click_method');
    $clickUrl = $app->config('click_url');
    if ($clickMethod === 'direct') {
        $url = Url::createFromServer($_SERVER);
        $url->setPath($clickUrl);
    } elseif ($clickMethod === 'redirect') {
        $url = Url::createFromUrl($clickUrl);
    }
    $currentQuery = $req->get();
    $currentQuery['id'] = $stepId;
    $url->getQuery()->modify($currentQuery);
    $app->redirect($url);

})->name('click')->conditions(array('stepId' => '[0-9]+'));
