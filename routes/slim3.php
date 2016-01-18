<?php

$app->get('/content/{id:[0-9]+}', function ($req, $res, $args) {
    $res = $this->get('pdo')->query('SELECT * FROM landers WHERE id = '.$args['id']);
    echo '<pre>';
    $cs = $this->get('crazyService');
    echo "Welcome to " . $args['id'] . PHP_EOL;
    // echo $cs() . PHP_EOL;
    foreach ($res as $r) {
        print_r($r);
    }
    // echo $cs() . PHP_EOL;
    echo '</pre>';
});
