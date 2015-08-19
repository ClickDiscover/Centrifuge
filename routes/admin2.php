<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;


$app->group('/admin', function() use ($app, $centrifuge) {
    $app->group('/models', function() use ($app, $centrifuge) {
        $app->group('/products', function () use ($app, $centrifuge) {
            $app->get('/', function () use ($app, $centrifuge) {
                $fs = $centrifuge['fs'];
                $products = $centrifuge['db']->uncachedFetchAll('SELECT * FROM products');
                $productRoot = $centrifuge['config']['paths']['relative_product'];
                $files = $fs->listWith(['mimetype', 'timestamp'], $productRoot);
                $files = array_filter($files, function ($x) { return(strpos($x['mimetype'], 'image') !== false); });
                $files = array_map(function ($x) { return "/" . $x['path']; }, $files);
                $existing = array_map(function ($x) use ($productRoot) { return $productRoot . $x['image_url']; }, $products);
                $unused = array_diff($files, $existing);

                return $app->render('admin::models/products', array(
                    'products' => $products,
                    'existing' => $existing,
                    'unused' => $unused
                ));
            });

            $app->post('/', function () use ($app, $centrifuge) {
                $name = $app->request->post('name');
                $url = $app->request->post('image_url');
                $centrifuge['offer.network']->insert($name, $url);
                $app->redirect('/admin/models/products');
            });
        });


        $app->group('/landers', function () use ($app, $centrifuge) {
            $app->get('/:id', function ($id) use ($app, $centrifuge) {
                $lander = $centrifuge['landers']->fetch($id);
                $template = $centrifuge['plates']->landerTemplate($lander);
                $data = $template->getData();
                $data['template'] =  $template->getData();
                $data['lander'] = $lander;
                $app->render('admin::testing/base', $data);
            })->conditions(['id' => '[0-9]+']);

            $app->get('/', function () use ($app, $centrifuge) {
                $variants = [];
                $landers = array_map(function ($x) {
                    return \Flagship\Util\FlattenObjects::lander($x);
                }, $centrifuge['landers']->fetchAll());
                return $app->render('admin::models/testing', ['landers' => $landers]);
            });
        });

        $app->get('/', function () use ($app, $centrifuge) {

        });
    });
});


$app->get('/admin/phpinfo', function() {
    return phpinfo();
});
$app->get('/admin/ping', function () use ($app) {
    echo "pong!";
});


// function cleanAeParams($aeParams) {
//     $clean = array();
//     foreach ($aeParams as $i => $ae) {
//         $clean[$i] = $ae;
//         if(!isset($ae['name'])) {
//             $clean[$i]['name'] = implode(' | ', array(
//                 $ae['affiliate_id'],
//                 $ae['country'],
//                 $ae['vertical']
//             ));
//         }
//     }
//     return $clean;
// }

// function set_alt(&$arr, &$alt, $key) {
//     $arr[$key] = $value;
//     unset($arr[$key]);
// }

// function landersServedAllTheWay($db, $products, $aeParams) {
//     $pns     = array_column($products, 'name', 'id');
//     $adex    = array_column($aeParams, 'name', 'id');
//     $sql     = "SELECT l.*, w.name AS website_name FROM landers l INNER JOIN websites w ON (w.id = l.website_id) ORDER BY id DESC";
//     $landers = array();
//     foreach($db->query($sql, PDO::FETCH_ASSOC) as $row) {
//         $row['website_id'] = $row['website_name'];
//         unset($row['website_name']);

//         if ($row['offer'] === 'network') {
//             $row['product1_id'] = $pns[$row['product1_id']];
//             $row['product2_id'] = $pns[$row['product2_id']];
//         } elseif ($row['offer'] === 'adexchange') {
//             $row['param_id'] = $adex[$row['param_id']];
//         }

//         // Put notes at end...
//         $notes = $row['notes'];
//         unset($row['notes']);
//         $row['notes'] = $notes;
//         $landers[] = $row;
//     }
//     return $landers;
// }

// function allModels($c) {
//     $container = new \Pimple\Container;

//     $container['products'] = function () use ($c) {
//         return $c['db']->uncachedFetchAll('SELECT * FROM products');
//     }

//     $container['websites'] = function () use ($c) {
//         return $c['db']->uncachedFetchAll('SELECT * FROM websites');
//     }

//     $container['routes'] = function () use ($c) {
//         return $c['db']->uncachedFetchAll('SELECT * FROM routes');
//     }

//     $container['adex'] = function () use ($c) {
//         return cleanAEParams($c['db']->uncachedFetchAll('SELECT * FROM ae_parameters'));

//     $container['product.page'] = function () use ($c, $container) {
//         $products = $container['products'];
//         $productRoot = $c['config']['paths']['relative_product'];
//         $files = $fs->listWith(['mimetype', 'timestamp'], $productRoot);
//         $files = array_filter($files, function ($x) { return(strpos($x['mimetype'], 'image') !== false); });
//         $files = array_map(function ($x) { return "/" . $x['path']; }, $files);
//         $existing = array_map(function ($x) use ($productRoot) { return $productRoot . $x['image_url']; }, $products);
//         $unused = array_diff($files, $existing);
//         return array(
//             'products' => $products,
//             'existing' => $existing,
//             'unused' => $unused
//         );
//     };

// }

