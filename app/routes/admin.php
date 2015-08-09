<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
require_once CENTRIFUGE_MODELS_ROOT . "/product.php";
require_once CENTRIFUGE_MODELS_ROOT . "/lander.php";
require_once CENTRIFUGE_MODELS_ROOT . "/route.php";

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;


function cleanAeParams($aeParams) {
    $clean = array();
    foreach ($aeParams as $i => $ae) {
        $clean[$i] = $ae;
        if(!isset($ae['name'])) {
            $clean[$i]['name'] = implode(' | ', array(
                $ae['affiliate_id'],
                $ae['country'],
                $ae['vertical']
            ));
        }
    }
    return $clean;
}


$app->path('admin', function($req) use ($app) {
    $app->path('phpinfo', function() {
        return phpinfo();
    });
    $app->path('ping', function ($req) use ($app) {
        return "pong!";
    });

    $app->path('models', function() use ($app) {
        $websites = $app->db()->query('SELECT * FROM websites')->fetchAll(PDO::FETCH_ASSOC);
        $products = $app->db()->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);
        $routes   = $app->db()->query('SELECT * FROM routes')->fetchAll(PDO::FETCH_ASSOC);
        $landers  = $app->db()->query('SELECT * FROM landers')->fetchAll(PDO::FETCH_ASSOC);
        $aeParams = cleanAEParams($app->db()->query('SELECT * FROM ae_parameters')->fetchAll(PDO::FETCH_ASSOC));
        $allModels = array(
            "websites" => $websites,
            "products" => $products,
            "routes" => $routes,
            "aeParams" => $aeParams,
            "landers" => $landers
        );

        $app->get(function () use ($app, $allModels) {
            $config = array(
                "OBJ_TTL" => OBJ_TTL,
                "CENTRIFUGE_ENV" => CENTRIFUGE_ENV,
                "FALLBACK_LANDER" => FALLBACK_LANDER,
                "CLICK_URL" => CLICK_URL,
                "CLICK_METHOD" => CLICK_METHOD,
                "FALLBACK_LANDER" => FALLBACK_LANDER,
                "ENABLE_LANDER_TRACKING" => ENABLE_LANDER_TRACKING,
                "CENTRIFUGE_STATIC_ROOT" => CENTRIFUGE_STATIC_ROOT,
                "CENTRIFUGE_PRODUCT_ROOT" => CENTRIFUGE_PRODUCT_ROOT
            );
            $allModels['config'] = $config;
            return $app->plates->render('admin::models/base', $allModels);
        });


        $app->path('products', function() use ($app, $products) {
            $app->get(function () use ($app, $products) {
                $adapter = new Local(CENTRIFUGE_WEB_ROOT . CENTRIFUGE_PRODUCT_ROOT);
                $fs = new Filesystem($adapter);
                $fs->addPlugin(new League\Flysystem\Plugin\ListWith);
                $files = $fs->listWith(['mimetype', 'timestamp']);
                $files = array_filter($files, function ($x) { return(strpos($x['mimetype'], 'image') !== false); });
                $files = array_map(function ($x) { return CENTRIFUGE_PRODUCT_ROOT . $x['path']; }, $files);
                $existing = array_map(function ($x) { return CENTRIFUGE_PRODUCT_ROOT . $x['image_url']; }, $products);
                $unused = array_diff($files, $existing);

                return $app->plates->render('admin::models/products', array(
                    'products' => $products,
                    'existing' => $existing,
                    'unused' => $unused
                ));
            });

            $app->post(function ($req) use ($app) {
                $n = $req->post()['name'];
                $url = $req->post()['image_url'];
                NetworkProduct::insert($app, $n, $url);
                return $app->response()->redirect('/admin/models/products');
            });
        });

        $app->path('landers', function ($req) use ($app, $allModels) {
            $app->param('int', function ($req, $id) use ($app) {
                $lander = LanderFunctions::fetch($app, $id);
                $app->get(function () use ($app, $lander)  {
                    return $app->plates->render('admin::testing/base', $lander->toArray());
                });
            });

            $app->get(function () use ($app, $allModels) {
                return $app->plates->render('admin::models/landers', $allModels);
            });

            $app->post(function ($req) use ($app) {
                $post = $req->post();
                // Extract Route
                $route = null;
                if ($post['route'] != '') {
                    $route = $post['route'];
                }
                unset($post['route']);

                // Insert lander
                $landerId = LanderFunctions::insert($app, $post);

                // Insert route
                if (isset($route)) {
                    $routeRes = RouteFunctions::insert($app, $route, $landerId);
                    $app->cache->getItem('routes')->clear();
                }

                // Redirect to admin page
                return $app->response()->redirect('/admin/models/landers');
            });
        });

        $app->path('adexchange', function () use ($app) {
            $app->post(function ($req) use ($app) {
                AdExchangeProduct::insert($app, $req->post());
                return $app->response()->redirect('/admin/models');
            });
        });
    });
});

