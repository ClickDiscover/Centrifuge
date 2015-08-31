<?php



$app->get('/conversions', function() use ($app, $centrifuge) {
    $config = $centrifuge['config']['database'];
    $redis = new Predis\Client($config['redis']);
    $total = $redis->get('interceptor:conversions:totals');
    $keys = $redis->keys('interceptor:conversions:keywords:*');

    $data = array();
    foreach ($keys as $k) {
        $keyword = array_reverse(explode(':', $k))[0];
        $data[$keyword] = $redis->get($k);
        $centrifuge['librato.performance']->breakout('keyword', $keyword, 'conversions', $data[$keyword]);
    }
    $centrifuge['librato.performance']->total('conversions', $total);

    echo '<pre>Totals: ' . $total . PHP_EOL;
    echo 'By keyword' . PHP_EOL;
    print_r($data);
    echo '</pre>';
});
