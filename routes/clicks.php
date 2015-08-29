<?php
use League\Url\Url;



function extractLanderFromRequest($req) {
    $refer = $req->getReferer();
    $qs = $req->get('fp_lid');
    if ($refer != "") {
        $refer = Url::createFromUrl($refer);
        $path = $refer->getPath()->toArray();
        $id = array_pop($path);
        $referHost = ltrim ($refer->setScheme(null)->getBaseUrl(), '/');
        if ($referHost == $req->getHostWithPort()) {
            return $id;
        }
    } elseif (isset($qs)) {
        return $qs;
    }
}

$app->get('/click/:stepId', function ($stepId) use ($app, $centrifuge) {
    $req = $app->request;
    $centrifuge['librato.performance']->total("clicks");

    // Lander Tracking
    $landerId = extractLanderFromRequest($req);
    if (isset($landerId)) {
        $lander = $centrifuge['landers']->fetch($landerId);
        $centrifuge['librato.performance']->breakout('lander', $landerId, 'clicks');
        $centrifuge['segment']->offerCLick($app->environment['tracking'], $lander);
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
    if ($clickMethod === 'direct') {
        $url = Url::createFromServer($_SERVER);
        $url->setPath($clickUrl);
    } elseif ($clickMethod === 'redirect') {
        $url = Url::createFromUrl($clickUrl);
    }
    // $currentQuery = Url::createFromServer($_SERVER)->getQuery()->toArray();
    $currentQuery = $req->get();
    $currentQuery['id'] = $stepId;
    $url->getQuery()->modify($currentQuery);
    $app->redirect($url);

})->name('click')->conditions(array('stepId' => '[0-9]+'));
