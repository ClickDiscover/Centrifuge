<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
require_once CENTRIFUGE_MODELS_ROOT . "/product.php";



function array_to_html($arr) {
    $out = '<table>';
    foreach ($arr as $row) {
        $out .= '<tr>';
        foreach ($row as $v) {
            $out .= "<td>{$v}</td>";
        }
        $out .= '</tr>';
    }
    return $out . '</table>';
}


$app->path('admin', function($req) use ($app) {
    $app->path('info', function() {
        return phpinfo();
    });

    $app->path('products', function() use ($app) {
        $results = $app->db()->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);
        $out = '<table>';
        foreach ($results as $prod) {
            $p = NetworkProduct::fromArray($prod, $app['PRODUCT_ROOT']);
            $out .= '<tr>';
            $out .= "<td>{$p->getId()}</td>";
            $out .= "<td>{$p->getName()}</td>";
            $out .= "<td>{$p->getImageUrl()}</td>";
            $out .= "<td><img src=\"{$p->getImageUrl()}\"/></td>";
            $out .= '</tr>';
        }
        return $out;
    });

    $app->path('landers', function ($req) use ($app) {
        $app->param('int', function ($req, $id) use ($app) {
            $lander = LanderFunctions::fetch($app, $id);
            $app->get(function () use ($app, $lander)  {
                return $app->plates->render('admin::testing', $lander->toArray());
            });
        });

    });


    $app->path('create', function ($req) use ($app) {
        $app->get(function () use ($app) {
            $websites = $app->db()->query('SELECT * FROM websites')->fetchAll(PDO::FETCH_ASSOC);;
            $products = $app->db()->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);;
            $routes = $app->db()->query('SELECT * FROM routes')->fetchAll(PDO::FETCH_ASSOC);;
            $landers = $app->db()->query('SELECT * FROM landers')->fetchAll(PDO::FETCH_ASSOC);;
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

        $app->post(function ($req) {
            $out = "<pre>";
            $out .= print_r($req->post(), true);
            $out .= "</pre>";
            return $out;
        });
    });

    $app->path('ping', function ($req) use ($app) {
        return "pong!";
    });
    $app->path('test', function() use ($app) {
        $s = print_r($_SERVER, true);
        return "<pre>{$s}</pre>";
    });

});

