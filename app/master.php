<?php
require_once __DIR__ . '/instruments.php';
require_once __DIR__ . '/util/variant.php';
// Setup defaults...
// error_reporting(-1); // Display ALL errors
// ini_set('display_errors', '1');
// ini_set("session.cookie_httponly", '1'); // Mitigate XSS javascript cookie attacks for browers that support it
// ini_set("session.use_only_cookies", '1'); // Don't allow session_id in URLs

// ENV globals
define('BULLET_ENV', $request->env('BULLET_ENV', 'development'));

// Production setting switch
// if(BULLET_ENV == 'production') {
//     // Hide errors in production
//     error_reporting(0);
//     ini_set('display_errors', '0');
// }

// Throw Exceptions for everything so we can see the errors
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
      throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler("exception_error_handler");
// Start user session
// session_start();

function cachedQuery($app, $type, $sql) {
    $item = $app->cache->getItem($type);
    $result = $item->get();
    if($item->isMiss()) {
        $app->log->info("Cache miss " . $type);
        $app->metrics->increment("cache_miss");
        $result = $app->db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $item->set($result, OBJ_TTL);
    }
    return $result;
}

$app['LANDER_ROOT'] = CENTRIFUGE_STATIC_ROOT;
$app['PRODUCT_ROOT'] = CENTRIFUGE_PRODUCT_ROOT;


$app->log     = $log;
// $app->db      = $db;
$app->addMethod('db', $db);
$app->cache   = $cache;
$app->metrics = $metrics;
$app->metrics->increment("num_requests");

$app->plates = new League\Plates\Engine(CENTRIFUGE_WEB_ROOT . "/landers");
$app->plates->loadExtension(new VariantExtension);
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
            $app->metrics->increment("errors");
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
        // $app->metrics->increment("errors");
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
