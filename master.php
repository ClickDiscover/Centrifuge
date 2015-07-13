<?php

require_once "defines.php";
require 'vendor/autoload.php';
include "models/lander.php";

use League\Url\Url;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


$templates = new League\Plates\Engine(__DIR__.'/templates/');
$db = new PDO(PDO_URL);

$router = new League\Route\RouteCollection;
$router->get('/silly/{id}', function (Request $request, Response $response, $args) {
    global $db, $templates;
    $lander = LanderFunctions::fetch($db, $args['id']);

    $content = $templates->render($lander->template, [
        'steps' => $lander->steps,
        'tracking' => $lander->tracking,
        'assets' => $lander->assetDirectory
    ]);

    $response->setContent($content);
    $response->setStatusCode(200);
    return $response;
});

$dispatcher = $router->getDispatcher();
$request = Request::createFromGlobals();
$uri = $request->getPathInfo();
if (substr($uri, 0, 6) !== '/silly') {
    $host = Url::createFromServer($_SERVER)->getBaseUrl();
    $url = $host . "/" . $uri;
    print_r($url);
    $response = new RedirectResponse($url);
} else {
    print_r($uri)."<BR>";
    $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
}
$response->send();

