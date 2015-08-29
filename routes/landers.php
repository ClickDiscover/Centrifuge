<?php

use Flagship\Storage\CookieJar;


function paramWithContext($app, $tracking, $key, $context = 'context', $subcontext='campaign') {
    $keyword = $app->request->params($key);
    if (isset($tracking[$context][$subcontext][$key])) {
        $keyword = $tracking[$context][$subcontext][$key];
    }
    return $keyword;
}


function segmentPage($app, $centrifuge, $lander) {

    $tracking = $app->environment['tracking'];
    $jar = $centrifuge['cookie.jar'];

    if (empty($tracking['flagship.id'])) {
        // Could return anon id.
        return false;
    }
    $userId = $tracking['flagship.id'];

    if (empty($_SESSION['_fp_segment']) || !$_SESSION['_fp_segment'] ) {
        if (empty($jar->getCookie('_fp_segment'))) {
            Segment::identify([
                'userId' => $userId
            ]);
            $_SESSION['_fp_segment'] = true;
            $jar->setCookie('_fp_segment', $userId, CookieJar::MONTHS);
        }

        $segmentId = $jar->getCookie('_fp_segment');
        if ($segmentId != $userId) {
            $centrifuge['logger']->warn('Segment ID differs from User ID', [$userId, $segmentId, $tracking]);
        }
    }

    $context = [];
    if (isset($tracking['context']['user']['ip'])) {
        $context['ip'] = $tracking['context']['user']['ip'];
    }
    if (isset($tracking['context']['user']['user_agent'])) {
        $context['userAgent'] = $tracking['context']['user']['user_agent'];
    }
    if (isset($tracking['context']['campaign']['ad'])) {
        $context['ad'] = $tracking['context']['campaign']['ad'];
    }
    if (isset($tracking['context']['campaign']['keyword'])) {
        $context['keyword'] = $tracking['context']['campaign']['keyword'];
    }

    $properties = array(
        'lander_id' => $lander->id,
        'title' => $lander->notes,
        'website' => $lander->website->name,
    );
    if (isset($tracking['context']['url']['url'])) {
        $properties['url'] = $tracking['context']['url']['url'];
    }
    if (isset($tracking['context']['url']['path'])) {
        $properties['path'] = $tracking['context']['url']['path'];
    }
    $properties['offer_source'] = $lander->offers[1]->product->source;
    $properties['offer1'] = $lander->offers[1]->getName();
    $properties['offer2'] = $lander->offers[2]->getName();

    // Segment::page(array(
    return array(
        'userId' => $userId,
        'name' => 'Landing Pageview',
        'properties' => $properties,
        'context' => $context
   );
}

$app->get('/content/:id', function ($id) use ($app, $centrifuge) {

    $lander = $centrifuge['landers']->fetch($id);

    if (!$lander) {
        $app->notFound();
    }


    // View tracking
    $tracking = $app->environment['tracking'];
    $centrifuge['librato.performance']->total("views");
    $centrifuge['librato.performance']->breakout('lander', $lander->id, 'views');
    $tracking['lander.id'] = $lander->id;

    // Not sure if I care about this as a flag
    // $enabled = $app->config('flags');
    // if ($enabled['lander_tracking']) {
        // if ($req->isBot()) {
        //     $app->system->total('bot_hits');
        //     $app->log->warning("Bot Error", $_SERVER);
        // }

    // Campaign + Keyword Tracking
    $keyword = paramWithContext($app, $tracking, 'keyword');
    if (isset($keyword)) {
        $centrifuge['librato.performance']->breakout('keyword', $keyword, 'views');
    }
    $ad = paramWithContext($app, $tracking, 'ad');
    // Segment.io tracking

    $jar = $centrifuge['cookie.jar'];
    $pg = segmentPage($app, $centrifuge, $lander);
    Segment::page($pg);

    $template = $centrifuge['plates']->landerTemplate($lander);

    $data = $template->getData();
    $data['pg'] = $pg;
    $app->render($template->getFile(), $data);
    // $app->render($template->getFile(), $template->getData());

})->name('landers')->conditions(array(
    'id' => '[0-9]+'
));


$app->get('/landers/:id', function ($id) use ($app) {
    $app->redirect($app->urlFor('landers', array('id' => $id)));
});
