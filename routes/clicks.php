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

$app->get('/click/:stepId', function ($stepId) use ($app) {
    // $app->performance->total("clicks");
    $req = $app->request;

    // Lander Tracking
    $landerId = extractLanderFromRequest($req);
    // if (ENABLE_LANDER_TRACKING && isset($landerId)) {
    //     $app->performance->breakout('lander', $landerId, 'clicks');
    // }

    // Keyword Tracking
    $keyword = $req->get('keyword');
    // if (isset($keyword)) {
    //     $app->performance->breakout('keyword', $keyword, 'clicks');
    // }

    // Now we redirect to cpv.flagshippromotions.com/base2.php
    // Eventually it will go to our campaign managment system
    $url = null;
    $centrifuge = $app->container['centrifuge'];
    $conf = $centrifuge['application'];
    if ($conf['click_method'] === 'direct') {
        $url = Url::createFromServer($_SERVER);
        $url->setPath($conf['click_url']);
    } elseif ($conf['click_method'] === 'redirect') {
        $url = Url::createFromUrl($conf['click_url']);
    }
    $currentQuery = Url::createFromServer($_SERVER)->getQuery()->toArray();
    $currentQuery['id'] = $stepId;
    $url->getQuery()->modify($currentQuery);
    echo $url;
    $app->redirect($url);

})->conditions(array(
    'stepId' => '[0-9]+'
));
