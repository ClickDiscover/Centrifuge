<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
require_once CENTRIFUGE_MODELS_ROOT . "/product.php";




$app->path('admin', function($req) use ($app) {
    $app->path('phpinfo', function() {
        return phpinfo();
    });
    $app->path('ping', function ($req) use ($app) {
        return "pong!";
    });

    $app->path('products', function() use ($app) {
        $app->get(function () use ($app) {
            $results = $app->db()->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);
            return $app->plates->render('admin::products', array(
                'products' => $results
            ));
        });
    });

    $app->path('landers', function ($req) use ($app) {
        $app->param('int', function ($req, $id) use ($app) {
            $lander = LanderFunctions::fetch($app, $id);
            $app->get(function () use ($app, $lander)  {
                return $app->plates->render('admin::testing/base', $lander->toArray());
            });
        });
    });


    $app->path('create', function ($req) use ($app) {
        $app->get(function () use ($app) {
            $websites = $app->db()->query('SELECT * FROM websites')->fetchAll(PDO::FETCH_ASSOC);;
            $products = $app->db()->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);;
            $routes   = $app->db()->query('SELECT * FROM routes')->fetchAll(PDO::FETCH_ASSOC);;
            $landers  = $app->db()->query('SELECT * FROM landers')->fetchAll(PDO::FETCH_ASSOC);;
            $aeParams = $app->db()->query('SELECT * FROM ae_parameters')->fetchAll(PDO::FETCH_ASSOC);;

            foreach ($aeParams as $i => $ae) {
                if(!isset($ae['name'])) {
                    $aeParams[$i]['name'] = implode(' | ', array(
                        $ae['affiliate_id'],
                        $ae['country'],
                        $ae['vertical']
                    ));
                }
            }

            return $app->plates->render('admin::landers', array(
                "websites" => $websites,
                "products" => $products,
                "routes" => $routes,
                "aeParams" => $aeParams,
                "landers" => $landers
            ));
        });

        $app->post(function ($req) use ($app) {
            $result = LanderFunctions::insert($app, $req->post());
            $out = "<pre>";
            $out .= "LanderFunctions::insert" . PHP_EOL;
            $out .= print_r($result, true);
            $out .= "</pre>";
            return $out;
        });
    });


});

