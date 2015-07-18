<?php
require_once dirname(__DIR__) . '/config.php';
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

$app['LANDER_ROOT'] = CENTRIFUGE_STATIC_ROOT;
$app['PRODUCT_ROOT'] = CENTRIFUGE_PRODUCT_ROOT;


$app->log = new Monolog\Logger('centrifuge');
$app->log->pushHandler(new Monolog\Handler\StreamHandler(CENTRIFUGE_LOG_ROOT, Monolog\Logger::INFO));


$app->db = new PDO(PDO_URL);


$connection = new \Domnikl\Statsd\Connection\UdpSocket('localhost', 8125);
$app->metrics = new \Domnikl\Statsd\Client($connection, 'centrifuge');
$app->metrics->increment("num_requests");

// $app->metrics = new Metrics\Client('patrick@flagshippromotions.com', '5c4e32ae950c2d8c09280b6e060a8a46294c4865d65be68317ebc7c047e2b62f');
// $librato_source = 'centrifuge.laptop';
// $librato_metric = array('name' => 'testing.app.centrifuge.fluxinator', 'value' => (rand(1, 100) / 13.0) + rand(0, 30));
// $librato_counter  = array('name' => 'testing.app.centrifuge.num_requests', 'value' => 1);
// $librato_res = $app->metrics->post('/metrics', array(
//     'source' => $librato_source,
//     'gauges' => array($librato_metric),
//     'counters' => array($librato_counter)
// ));

// echo '<pre>';
// echo "Librato counter:";
// print_r($librato_counter);
// echo "Librato metric:";
// print_r($librato_metric);
// echo "Librato result:";
// print_r($librato_res);
// echo '</pre>';

$app->plates = new League\Plates\Engine(CENTRIFUGE_WEB_ROOT . "/landers");
$app->plates->loadExtension(new VariantExtension);
$app->plates->addFolder('admin', CENTRIFUGE_WEB_ROOT. '/admin');
foreach ($app->db->query('SELECT distinct namespace from websites', PDO::FETCH_COLUMN, 0) as $ns) {
    $app->plates->addFolder($ns, CENTRIFUGE_WEB_ROOT . '/landers/' . $ns);
}



// Display exceptions with error and 500 status
$app->on('Exception', function(\Bullet\Request $request, \Bullet\Response $response, \Exception $e) use($app) {
    $data = array(
        // 'error' => str_replace('Exception', '', get_class($e)),
        'error' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'code' => $e->getCode()
    );
    // $data['trace'] = $e->getTrace();

    $out = '<strong>'. get_class($e). '</strong><pre>' . print_r($data, true) . '</pre>';
    $response->content($out);
    if(BULLET_ENV === 'production') {
        // An error happened in production. You should really let yourself know about it.
        // TODO: Email, log to file, or send to error-logging service like Sentry, Airbrake, etc.
    }
});

$app->on('LanderNotFoundException', function ($req, $res, \Exception $e) use($app) {
    $data = array(
        // 'error' => str_replace('Exception', '', get_class($e)),
        'error' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'code' => $e->getCode()
    );
    // $data['trace'] = $e->getTrace();

    $out = '<strong>'. get_class($e). '</strong><pre>' . print_r($data, true) . '</pre>';
    $res->status(200);
    $res->content($out);
});

// Custom 404 Error Page
$app->on(404, function(\Bullet\Request $request, \Bullet\Response $response) use($app) {
    $message = "Whoa! " . $request->url() . " wasn't found!";
    $response->content($message);
});


$app->on('before', function ($request, $response) use ($app) {
    $routes = $app->db->query("SELECT * FROM routes", PDO::FETCH_ASSOC);
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
