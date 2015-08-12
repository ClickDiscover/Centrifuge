<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';




$app->path('conversions', function() use ($app) {
    $redis = new Predis\Client(REDIS_URL);

    $app->get(function ($req) use ($app, $redis) {
        $total = $redis->get('interceptor:conversions:totals');
        $app->performance->total('conversions', $total);

        $keys = $redis->keys('interceptor:conversions:keywords:*');
        $data = array();
        foreach ($keys as $k) {
            $keyword = array_reverse(explode(':', $k))[0];
            $data[$keyword] = $redis->get($k);
            $app->performance->breakout('keyword', $keyword, 'conversions', $data[$keyword]);
        }
        echo '<pre>Totals: ' . $total . PHP_EOL;
        echo 'By keyword' . PHP_EOL;
        print_r($data);
        echo '</pre>';

        return 200;
    });
});
