<?php

require_once "defines.php";
require 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$router = new League\Route\RouteCollection;
$router->get('/silly', function (Request $request, Response $response) {
    $response->setContent('Silly');
    $response->setStatusCode(200);
    return $response;
});

$dispatcher = $router->getDispatcher();
$request = Request::createFromGlobals();
print_r($request->getMethod());
print_r($request->getPathInfo());

$response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
$response->send();

