<?php
require_once dirname(__DIR__) . '/config.php';

require BULLET_ROOT . '/vendor/autoload.php';

// include "models/lander.php";

// use League\Url\Url;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\RedirectResponse;


// $templates = new League\Plates\Engine(__DIR__.'/templates/');
// $db = new PDO(PDO_URL);

// function baseController (Request $request, Response $response, $args) {
//     global $db, $templates;
//     $lander = LanderFunctions::fetch($db, $args['id']);

//     $content = $templates->render($lander->template, [
//         'steps' => $lander->steps,
//         'tracking' => $lander->tracking,
//         'assets' => $lander->assetDirectory
//     ]);

//     $response->setContent($content);
//     $response->setStatusCode(200);
//     return $response;
// };

// $router = new League\Route\RouteCollection;
// $router->get('/silly/{id}', 'baseController');
// $router->get('/crazy_blog/article', function ($req, $res) {
//     return baseController($req, $res, array('id' => 3));
// });
// $dispatcher = $router->getDispatcher();








// // Stupid routes.php file
// $request = Request::createFromGlobals();
// $uri = $request->getPathInfo();
// if (substr($uri, 0, 6) !== '/silly' && substr($uri, 0, 4) !== '/cra') {
//     $host = Url::createFromServer($_SERVER)->getBaseUrl();
//     $url = $host . "/" . $uri;
//     print_r($url);
//     $response = new RedirectResponse($url);
// } else {
//     print_r($uri)."<BR>";
//     $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
// }
// $response->send();

