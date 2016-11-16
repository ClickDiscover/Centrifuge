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
                $productRoot = $centrifuge['config']['paths']['relative.product'];
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


    // $app->get('/tracking', $app->container['route_middleware.viewAdmin'], function () use ($app, $centrifuge) {
    $app->get('/tracking', function () use ($app, $centrifuge) {
        // $user = $app->environment['user'];
        // $eventType = "view";
        // $lander = $centrifuge['landers']->fetch($app->request->get('lid', 1));
        // $view = new \Flagship\Event\View($centrifuge['random.id'], $user, $app->environment['contexts'], $lander);
        // if (isset($lander)) {
        //     ($eventType == 'click') ? $view->setStepId(2) : null;
        //     $view->setLander($lander);
        // }

        // $out  = "\n\n" . $eventType ."::getSegmentArray\n";
        // $out .= print_r($view->getSegmentArray(), true);
        // $out  = "\nUser\n";
        // $out .= print_r($user, true);
        // // $out .= print_r($view, true);
        // $out .= "\nTrackingCookie\n";
        // $out .= print_r($user->getCookie()->pretty(), true);
        // $out .= "\n\n\nSession\n";
        // $out .= print_r($_SESSION, true);
        // $out .= "\nCookies\n";
        // $out .= print_r($app->request->cookies->all(), true);

        echo $centrifuge['plates']->render('admin::models/layout', [
            'title' => 'Tracking',
            'data' => "Disabled"
        ]);
    });

    $app->group('/control', function() use ($app, $centrifuge) {
        $app->get('/', function () use ($app, $centrifuge) {
            exec('cd '.CENTRIFUGE_ROOT.' && git log -1', $lcCmd);
            exec('cd '.CENTRIFUGE_WEB_ROOT.'/static && git log -1', $landerCmd);

            $centrifugeVersion = exec('cd '.CENTRIFUGE_ROOT.' && git describe --always');
            $landerVersion = exec('cd '.CENTRIFUGE_WEB_ROOT.'/static && git describe --always');

            $bundle = [
                'centrifuge_lc' => $lcCmd,
                'landers_lc' => $landerCmd,
                'centrifuge_version' => $centrifugeVersion,
                'lander_version' => $landerVersion
            ];
            $bundle['config'] = $centrifuge['config'];
            $app->render('admin::models/control', $bundle);
        });

        $app->post('/cache/clear', function () use ($app, $centrifuge) {
            $res = $app->response;
            $out  = 'Cleared Cache ';
            $out .= $centrifuge['cache']->flush();
            $res->setBody($out);
            $res->finalize();
        });
        $app->post('/session/clear', function () use ($app, $centrifuge) {
            $res = $app->response;
            $out  = 'Cleared Session Cache';
            $out .= $centrifuge['session.cache']->flush();
            $res->setBody($out);
            $res->finalize();
        });
    });

});


$app->get('/admin/phpinfo', function() {
    return phpinfo();
});
$app->get('/admin/ping', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    echo 'pong!';
});
$app->get('/status/ping', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    echo "pong!";
});


$app->get('/conversions', function() use ($app, $centrifuge) {
    // $convs = $centrifuge['conversions'];
    // $total = $convs->totals();
    // $keys = $convs->keywords();
    // $centrifuge['librato.performance']->total('conversions', $total);
    // foreach ($keys as $k => $v) {
        // $centrifuge['librato.performance']->breakout('keyword', $k, 'conversions', $v);
    // }

    // $out  = '<br>Totals: ' . $total . PHP_EOL;
    // $out .= 'By keyword' . PHP_EOL;
    // $out .= print_r($keys, true);
    echo $centrifuge['plates']->render('admin::models/layout', [
        'title' => 'Conversions',
        'data' => 'Disabled'
    ]);
});

// $app->group('/aerospike', function () use ($app, $centrifuge) {
    // $db = $centrifuge['aerospike'];

    // $app->get('/user/:id', function($id) use ($app, $db) {
        // echo '<pre>';
        // $user = $db->fetchById('users', $id);
        // // $user['bins']['segment.id'] = null;
        // print_r($user);
        // // $db->put($key, $user);
        // echo '</pre>';
    // });

    // $app->get('/delete/:what', function($what) use ($app, $db) {
        // $db = $db->db();
        // $count = 0;
        // echo '<pre>';
        // $db->scan('test', $what, function ($x) use ($db, $what, $count) {
            // $count++;
            // $key = $db->initKey('test', $what, $x['bins']['id']);
            // $rc = $db->remove($key);
            // print_r([$rc, $db->error(), $db->errorno()]);
        // });
        // echo '</pre>Deleted ' . $count . ' records';
    // });

    // $app->get('/get/:ns/:what', function($ns, $what) use ($app, $db) {
        // $db = $db->db();
        // echo "Scanning {$ns} for {$what}".PHP_EOL;
        // echo '<pre>';
        // $ns = $app->request->params('ns', 'test');
        // $db->scan($ns, $what, function ($x) use ($app, $db, $what) {
            // // $rec = $app->request->params('meta', false) ? $x : $x['bins'];
            // $rec = $x;
            // print_r($rec);
        // });
        // echo '</pre>';
    // });
// });

