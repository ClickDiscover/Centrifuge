<?php
require_once dirname(__DIR__) . '/config.php';
// Setup defaults...
error_reporting(-1); // Display ALL errors
// ini_set('display_errors', '1');
// ini_set("session.cookie_httponly", '1'); // Mitigate XSS javascript cookie attacks for browers that support it
// ini_set("session.use_only_cookies", '1'); // Don't allow session_id in URLs

// ENV globals
define('BULLET_ENV', $request->env('BULLET_ENV', 'development'));

// Production setting switch
if(BULLET_ENV == 'production') {
    // Hide errors in production
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Throw Exceptions for everything so we can see the errors
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
      throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler("exception_error_handler");

// Start user session
// session_start();

$app->plates = new League\Plates\Engine(BULLET_ROOT . "/landers/");
$app->db = new PDO(PDO_URL);


// Display exceptions with error and 500 status
$app->on('Exception', function(\Bullet\Request $request, \Bullet\Response $response, \Exception $e) use($app) {
    $data = array(
        'error' => str_replace('Exception', '', get_class($e)),
        'message' => $e->getMessage()
    );

    // Debugging info for development ENV
    // if(BULLET_ENV !== 'deve') {
    $data['file'] = $e->getFile();
    $data['line'] = $e->getLine();
    // $data['trace'] = $e->getTrace();
    // }

    $out = '<pre>' . print_r($data, true) . '</pre>';
    $response->content($out);

    if(BULLET_ENV === 'production') {
        // An error happened in production. You should really let yourself know about it.
        // TODO: Email, log to file, or send to error-logging service like Sentry, Airbrake, etc.
    }
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