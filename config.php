<?php

date_default_timezone_set('UTC');

define('CENTRIFUGE_ROOT', __DIR__);
define('CENTRIFUGE_WEB_ROOT', CENTRIFUGE_ROOT . '/www');
define('CENTRIFUGE_APP_ROOT', CENTRIFUGE_ROOT . '/app');
define('CENTRIFUGE_MODELS_ROOT', CENTRIFUGE_APP_ROOT . '/models');
define('CENTRIFUGE_STATIC_ROOT', '/static');
define('CENTRIFUGE_PRODUCT_ROOT', '/static/products/');
// define('CENTRIFUGE_LOG_ROOT', dirname(CENTRIFUGE_ROOT) . '/centrifuge-logs/app.log');
define('CENTRIFUGE_LOG_ROOT', '/tmp/centrifuge.log');
define('PDO_URL', 'pgsql:host=localhost;dbname=rotator;port=5432;user=patrick');
