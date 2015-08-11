<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
use League\Url\Url;



$app->path('click', function () use ($app) {
    $app->param('int', function ($req, $stepId) use ($app)  {

        $app->get(function ($req) use ($app, $stepId) {
            $currentQuery = Url::createFromServer($_SERVER)->getQuery()->toArray();
            $app->metrics->increment("centrifuge.clicks");
            if (ENABLE_LANDER_TRACKING && isset($currentQuery['lander'])) {
                $app->metrics->increment('lander.' . $currentQuery['lander'] . '-lander.clicks');
                unset($currentQuery['lander']);
            }

            $url = null;
            if (CLICK_METHOD == 'direct') {
                $url = Url::createFromServer($_SERVER);
                $url->setPath(CLICK_URL);
            } elseif (CLICK_METHOD == 'redirect') {
                $url = Url::createFromUrl(CLICK_URL);
            }

            $currentQuery['id'] = $stepId;
            $url->getQuery()->modify($currentQuery);
            return $app->response()->redirect($url);
        });
    });
});


