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
        $centrifuge['admin.geos'] = function () use ($centrifuge) {
            return array_map(function ($x) { return $x->toArray(); }, $centrifuge['landers']->fetchAllGeos());
        };
        $centrifuge['admin.landers'] = function () use ($centrifuge) {
            return $centrifuge['landers']->fetchAll(true);
        };
        $centrifuge['admin.bundle'] = function () use ($centrifuge) {
            return array(
                'websites' => $centrifuge['admin.websites'],
                'routes' => $centrifuge['admin.routes'],
                'geos' => $centrifuge['admin.geos'],
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


    $app->get('/tracking', $app->container['route_middleware.clickAdmin'], function () use ($app, $centrifuge) {
        // $lander = isset($_SESSION['last_lander']) ? $_SESSION['last_lander'] : null;
        $view = $app->environment['click'];
        $lander = $centrifuge['landers']->fetch($app->request->get('lid', 1));
        if (isset($lander)) {
            $view->setStepId(2);
            $view->setLander($lander);
        }
        $out  = "Click\n";
        $out .= print_r($view, true);
        $out .= "Click::toSegmetn\n";
        $out .= print_r($view->getSegmentArray(), true);
        $out .= "Session\n";
        $out .= print_r($_SESSION, true);
        $out .= "\nTracking\n";
        $out .= print_r($app->environment['tracking'], true);
        $out .= "\nCookies\n";
        $out .= print_r($app->request->cookies->all(), true);

        $out .= "\ngetHost " . $app->request->getHost() . "\n";
        $out .= "\ngetHostWithPort " . $app->request->getHostWithPort() . "\n";
        $out .= "\ngetScriptName " . $app->request->getScriptName() . "\n";
        $out .= "\ngetPath " . $app->request->getPath() . "\n";
        $out .= "\ngetPathInfo " . $app->request->getPathInfo() . "\n";
        $out .= "\ngetResourceUri " . $app->request->getResourceUri() . "\n";
        $out .= "\ngetUrl " . $app->request->getUrl() . "\n";

        echo $centrifuge['plates']->render('admin::models/layout', [
            'title' => 'Tracking',
            'data' => $out
        ]);
    });

});


$app->get('/admin/phpinfo', function() {
    return phpinfo();
});
$app->get('/admin/ping', function () use ($app) {
    echo "pong!";
});


$app->get('/conversions', function() use ($app, $centrifuge) {
    $convs = $centrifuge['conversions'];
    $total = $convs->totals();
    $keys = $convs->keywords();
    $centrifuge['librato.performance']->total('conversions', $total);
    foreach ($keys as $k => $v) {
        $centrifuge['librato.performance']->breakout('keyword', $k, 'conversions', $v);
    }

    $out  = '<br>Totals: ' . $total . PHP_EOL;
    $out .= 'By keyword' . PHP_EOL;
    $out .= print_r($keys, true);
    echo $centrifuge['plates']->render('admin::models/layout', [
        'title' => 'Conversions',
        'data' => $out
    ]);
});


$app->group('/admin/aero', function () use($app, $centrifuge) {
    $config = [
        "hosts" => [
            ["addr" => "localhost", "port" => 3000]
        ]
    ];
    $db = new Aerospike($config);

    $app->get('/put', function () use ($app, $db) {
        echo '<pre>';
        $tracking = $app->environment['tracking'];
        $id = $tracking['flagship.id'];
        $key = $db->initKey('test', 'users', $id);

        $option = [Aerospike::OPT_POLICY_KEY => Aerospike::POLICY_KEY_SEND];
        $lander = isset($_SESSION['last_lander']) ? $_SESSION['last_lander'] : null;
        if (isset($lander)) {
            $lkey = $db->initKey('test', 'landers', $lander->id);
            $db->increment($lkey, 'num_hits', 1, $option);
        }

        $tc = $tracking['cookie'];
        $bins = [
            'created' => $tc->getCreationTime(),
            'numVisits' => $tc->getVisitCount()
        ];
        $status = $db->put($key, $bins, 0, $option);
        if ($status == Aerospike::OK) {
            echo "Record written.\n";
        } else {
            echo "[{$db->errorno()}] ".$db->error();
        }
        echo 'Key: ' . print_r($key, 1) . PHP_EOL;
        echo 'bins: ' . print_r($bins, 1) . PHP_EOL;
        echo 'Status: ';
        var_dump($status);
        echo '</pre>';
    });

    $app->get('/get', function () use ($app, $db) {
        echo '<pre>';
        $tracking = $app->environment['tracking'];
        $id = $tracking['flagship.id'];
        $key = $db->initKey('test', 'users', $id);
        $status = $db->get($key, $record);
        if ($status == Aerospike::OK) {
            print_r($record);
        } elseif ($status == Aerospike::ERR_RECORD_NOT_FOUND) {
            echo "A user with key ". $key['key']. " does not exist in the database\n";
        } else {
            echo "[{$db->errorno()}] ".$db->error();
        }


        // echo PHP_EOL . PHP_EOL . 'Cookies: ' . PHP_EOL;
        // $db->scan('test', 'cookies', function ($x) use ($db) {
        //     print_r($x);
        // });

        echo PHP_EOL . PHP_EOL . 'Landers: ' . PHP_EOL;
        $db->scan('test', 'users', function ($x) use ($db) {
            print_r($x);
            // $key = $db->initKey($x['key']['ns'], $x['key']['set'], $x['key']['digest'], true);
            // $status = $db->remove($key);
            // echo "Removed " . $status;
        });
    });
});


