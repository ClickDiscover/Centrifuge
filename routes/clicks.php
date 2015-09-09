<?php
use League\Url\Url;




function landerIdFromRequest($req) {
    $qs    = $req->get('fp_lid');
    $refer = $req->getReferrer();
    if (isset($refer)) {
        $refer = Url::createFromUrl($refer);
        $path = $refer->getPath()->toArray();
        $id = array_pop($path);
        if ($refer->getHost() == $req->getHost()) {
            return $id;
        }
    } elseif (isset($qs)) {
        return $qs;
    }
}

function landerFromRequest($landers, $req) {
    if (isset($_SESSION['last_lander'])) {
        return $_SESSION['last_lander'];
    } else {
        $id = landerIdFromRequest($req);
        return (isset($id)) ? $landers->fetch($id) : null;
    }
}


$app->get('/click/:stepId', function ($stepId) use ($app, $centrifuge) {
    $req = $app->request;
    $centrifuge['librato.performance']->total("clicks");

    $tracking = $app->environment['tracking'];
    $tracking['click.step_id'] = $stepId;
    if (isset($tracking['cookie'])) {
        $tracking['cookie']->setLastOfferClickTime(time());
    }

    // Lander Tracking
    $lander = landerFromRequest($centrifuge['landers'], $req);
    if (isset($lander)) {
        // $centrifuge['logger']->info('Lander', [$lander->id]);
        $centrifuge['librato.performance']->breakout('lander', $lander->id, 'clicks');
        $centrifuge['segment']->offerClick($tracking, $lander);
    }


    // Keyword Tracking
    $keyword = $req->get('keyword');
    if (isset($keyword)) {
        $centrifuge['librato.performance']->breakout('keyword', $keyword, 'clicks');
    }

    // Now we redirect to cpv.flagshippromotions.com/base2.php
    // Eventually it will go to our campaign managment system
    $url = null;
    $clickMethod = $app->config('click_method');
    $clickUrl = $app->config('click_url');
    $stepName = $app->config('click_step_name');
    if ($clickMethod === 'direct') {
        $url = Url::createFromServer($_SERVER);
        $url->setPath($clickUrl);
    } elseif ($clickMethod === 'redirect') {
        $url = Url::createFromUrl($clickUrl);
    }
    $currentQuery = $req->get();
    $currentQuery[$stepName] = $stepId;
    $url->getQuery()->modify($currentQuery);

    $app->redirect($url);

})->name('click')->conditions(array('stepId' => '[0-9]+'));
