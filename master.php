<?php

require_once "defines.php";
require_once "models/product.php";
require_once "models/step.php";
require_once "models/tracking.php";
require 'vendor/autoload.php';

// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;

// $router = new League\Route\RouteCollection;
// $router->get('/silly', function (Request $request, Response $response) {
//     $response->setContent('Silly');
//     $response->setStatusCode(200);
//     return $response;
// });

// $dispatcher = $router->getDispatcher();
// $request = Request::createFromGlobals();
// print_r($request->getMethod());
// print_r($request->getPathInfo());

// if ($request->getPathInfo() != '/silly') {
//     $uri = '/' . $request->getPathInfo();
//     $response = $dispatcher->dispatch($request->getMethod(), $uri);
//     $response->send();
// }




$templates    = new League\Plates\Engine(__DIR__.'/templates/');
$lander_id = 2;

$db = new PDO(PDO_URL);
$sql = <<<SQL
    SELECT l.*, w.template, w.assets FROM landers l
    INNER JOIN websites w ON (w.id = l.website_id)
    WHERE l.id = {$lander_id};
SQL;
$res = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
$tracking = new Tracking($res['tracking_tags']);
$products = [];

if ($res['offer'] == 'adexchange') {
    $sql = "SELECT affiliate_id, vertical, country FROM ae_parameters WHERE id = ".$res['param_id'];
    $params = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
    $products = AdExchangeProduct::fetchFromAdExchange($params['affiliate_id'], $params['vertical'], $params['country']);
} elseif ($res['offer'] == 'network') {
    $sql = "SELECT name, image_url FROM products WHERE id IN (".implode(',', [$res['product1_id'], $res['product2_id']]).")";
    foreach ($db->query($sql)->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $products[] = NetworkProduct::fromArray($row);
    }
}

$steps = Step::fromProducts($products);
$template = substr($res['template'], 0, -4);
$assets = 'templates/' . $res['assets'];
echo $templates->render($template, ['steps' => $steps, 'tracking' => $tracking, 'assets' => $assets]);
