<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require BULLET_ROOT . '/vendor/autoload.php';
require_once BULLET_MODELS_ROOT . "/product.php";



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
        $results = $app->db->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);
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
});

