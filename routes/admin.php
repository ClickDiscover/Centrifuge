<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;


class ProductFinder {
    public static function go($fs, $path, $products) {
        $files = $fs->listWith(['mimetype', 'timestamp'], $path);
        $files = array_filter($files, function ($x) { return(strpos($x['mimetype'], 'image') !== false); });
        $files = array_map(function ($x) { return "/" . $x['path']; }, $files);
        $existing = array_map(function ($x) use ($path) { return $x->imageUrl; }, $products);
        $unused = array_diff($files, $existing);
        return array(
            'existing' => $existing,
            'unused' => $unused
        );
    }
}

class VariantFinder {
    public static function go($fs, $path) {
        $values = array();
        if ($fs->has($path)) {
            $contents = $fs->listContents($path, true);
            foreach ($contents as $c) {
                if ($c['type'] === 'file' && $c['filename'] !== 'default') {
                    $base = array_slice(explode('/', $c['dirname']), -1)[0];
                    $values[$base][] = $c['filename'];
                }
            }
        }
        return $values;
    }
}


$app->group('/admin', function() use ($app, $centrifuge) {

    $app->group('/models', function() use ($app, $centrifuge) {

        $centrifuge['admin.products'] = function () use ($centrifuge) {
            return $centrifuge['offer.network']->fetchAll();
        };
        $centrifuge['admin.websites'] = function () use ($centrifuge) {
            return $centrifuge['landers']->fetchAllWebsites();
        };
        $centrifuge['admin.routes'] = function () use ($centrifuge) {
            return $centrifuge['custom.routes']->fetchAll();
        };
        $centrifuge['admin.adex.params'] = function () use ($centrifuge) {
            return $centrifuge['offer.adex']->paramFetchAll();
        };
        $centrifuge['admin.landers'] = function () use ($centrifuge) {
            return array_map(function ($x) {
                return \Flagship\Util\FlattenObjects::lander($x);
            }, $centrifuge['landers']->fetchAll());
        };
        $centrifuge['admin.bundle'] = function () use ($centrifuge) {
            return array(
                'websites' => $centrifuge['admin.websites'],
                'routes' => $centrifuge['admin.routes'],
                'aeParams' => $centrifuge['admin.adex.params'],
                'landers' => $centrifuge['admin.landers'],
                'products' => $centrifuge['admin.products']
            );
        };



        $app->group('/products', function () use ($app, $centrifuge) {
            $app->get('/', function () use ($app, $centrifuge) {
                $fs = $centrifuge['fs'];
                $products = $centrifuge['admin.products'];
                $productRoot = $centrifuge['config']['paths']['relative_product'];
                $finder = ProductFinder::go($fs, $productRoot, $products);
                $finder['products'] = $products;
                return $app->render('admin::models/products', $finder);
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
                $app->render('admin::testing/base', $template->getData());
            })->conditions(['id' => '[0-9]+']);

            $app->get('/', function () use ($app, $centrifuge) {
                $bundle = $centrifuge['admin.bundle'];
                $variants = [];
                foreach ($bundle['websites'] as $ws) {
                    $path =  '/landers/' . $ws->namespace . '/variants';
                    $variants[$ws->id] = VariantFinder::go($centrifuge['fs'], $path);
                }
                $bundle['variants'] = $variants;
                return $app->render('admin::models/landers', $bundle);
            });

            $app->post('/', function () use ($app, $centrifuge) {
                $input = $app->request->post();
                $route = null;
                if ($input['route'] != '') {
                    $route = $input['route'];
                }
                unset($input['route']);

                $landerId = $centrifuge['landers']->insert($input);
                // $log->warn('route '. $route);
                if (isset($route)) {
                    $lid = $landerId['id'];
                    $res = $centrifuge['custom.routes']->insert($route, $lid);
                }
                $app->redirect('/admin/models/landers');
            });
        });

        $app->get('/', function () use ($app, $centrifuge) {
            $bundle = $centrifuge['admin.bundle'];
            $bundle['config'] = $centrifuge['config'];
            return $app->render('admin::models/base', $bundle);
        });

        $app->post('/adexchange', function () use ($app, $centrifuge) {
            $input = $app->request->post();
            $obj = new \Flagship\Model\AdexParameters();
            $obj->fromArray($input);
            $res = $centrifuge['offer.adex']->insert($obj);
            $app->redirect('/admin/models');
        });
    });


    $app->get('/tracking', function () use ($app, $centrifuge) {
        trackingPage($app, $centrifuge);
    });

});


$app->get('/admin/phpinfo', function() {
    return phpinfo();
});
$app->get('/admin/ping', function () use ($app) {
    echo "pong!";
});


function trackingPage($app, $centrifuge) {
    // sessions in slim route "Groups" arent excuted it seems
    echo "<pre>Session\n";
    // print_r($_SESSION);
    echo "\nTracking\n";
    print_r($app->environment['tracking']);
    echo "\nContext\n";
    $context = $app->environment['tracking']['context'];
    print_r($context);
    echo "</pre>";
}

