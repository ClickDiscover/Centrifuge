<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;


$app->get('/phpinfo', function() {
    return phpinfo();
});
$app->get('/ping', function () use ($app) {
    echo "pong!";
});


$app->group('/admin', function() use ($app, $centrifuge) {
    $app->group('/models', function() use ($app, $centrifuge) {
        $app->group('/products', function () use ($app, $centrifuge) {
            $app->get('/', function () use ($app, $centrifuge) {
                $fs = $centrifuge['fs'];
                $products = $centrifuge['db']->uncachedFetchAll('SELECT * FROM PRODUCTS');
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
            });
        });
    });
});

