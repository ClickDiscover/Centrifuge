<?php
// require_once dirname(dirname(__DIR__)) . '/config.php';
require dirname(dirname(__DIR__)) . '/vendor/autoload.php';



$app->get('/conversions', function() use ($app) {
    $config = $app->config('database');
    $redis = new Predis\Client($config['redis']);
    $total = $redis->get('interceptor:conversions:totals');
    $keys = $redis->keys('interceptor:conversions:keywords:*');

    $data = array();
    foreach ($keys as $k) {
        $keyword = array_reverse(explode(':', $k))[0];
        $keyword = str_replace('-', '', $keyword);
        $data[$keyword] = $redis->get($k);
        // $app->performance->breakout('keyword', $keyword, 'conversions', $data[$keyword]);
    }
    // $app->performance->total('conversions', $total);

    echo '<pre>Totals: ' . $total . PHP_EOL;
    echo 'By keyword' . PHP_EOL;
    print_r($data);
    echo '</pre>';
});
