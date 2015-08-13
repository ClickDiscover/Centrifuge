<?php
require_once __DIR__ . '/instruments.php';
require_once __DIR__ . '/util/variant.php';
require_once __DIR__ . '/util/html.php';

if (CENTRIFUGE_ENV == 'dev') {
    Symfony\Component\Debug\Debug::enable();
}

function cachedQuery($app, $type, $sql) {
    $item = $app->cache->getItem($type);
    $result = $item->get();
    if($item->isMiss()) {
        $app->log->info("Cache miss " . $type);
        $app->system->total("cache_miss");
        $result = $app->db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $item->set($result, OBJ_TTL);
    }
    return $result;
}


$app->log         = $log;
$app->cache       = $cache;
$app->metrics     = $metrics;
$app->system      = $systemMetrics;
$app->performance = $performanceMetrics;
$app->addMethod('db', $db);
$app->system->total("num_requests");

$app->plates = new League\Plates\Engine(CENTRIFUGE_WEB_ROOT . "/landers");
$app->plates->loadExtension(new VariantExtension);
$app->plates->loadExtension(new HtmlExtension);

$app->plates->addFolder('admin', CENTRIFUGE_WEB_ROOT. '/admin');
foreach (cachedQuery($app, "distinct/websites", "SELECT distinct namespace from websites") as $namespace) {
    $ns = $namespace['namespace'];
    $app->plates->addFolder($ns, CENTRIFUGE_WEB_ROOT . '/landers/' . $ns);
}



// Error handling
$app->on('Exception', function(\Bullet\Request $request, \Bullet\Response $response, \Exception $e) use($app) {
    $data = array(
        // 'error' => str_replace('Exception', '', get_class($e)),
        'error' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'code' => $e->getCode()
    );

    if(CENTRIFUGE_ENV  === 'production') {
        if (get_class($e) != "LanderNotFoundException") {
            $app->log->error((string) $e);
            $app->system->total("errors");
        }
        $response->content($app->run('get', '/landers/' . FALLBACK_LANDER));
    } elseif(CENTRIFUGE_ENV === 'dev') {
        $out = '<strong>'. get_class($e). '</strong><pre>' . print_r($data, true) . '</pre>';
        $response->content($out);
    }
});


$app->on(404, function(\Bullet\Request $request, \Bullet\Response $response) use($app) {
    if(CENTRIFUGE_ENV  === 'production') {
        $reqData = array(
            'query' => $request->query(),
            'server' => $request->server()
        );
        $app->log->warning('404', $reqData);
        $app->system->total("4xx");
        $response->content($app->run('get', '/landers/' . FALLBACK_LANDER));
    } elseif(CENTRIFUGE_ENV === 'dev') {
        $message = "Whoa! " . $request->url() . " wasn't found!";
        $response->content($message);
    }
});


// Route to custom URLs
$app->on('before', function ($request, $response) use ($app) {
    $routes = cachedQuery($app, 'routes', "SELECT * FROM routes");
    foreach ($routes as $r) {
        if ($request->url() == $r['url']) {
            $matched_id = $r['lander_id'];
        }
    }
    if (isset($matched_id)) {
        $response = $app->run('GET', '/landers/' . $matched_id);
        $response->send();
        exit;
    }
});
