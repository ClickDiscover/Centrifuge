<?php

date_default_timezone_set('UTC');

define('CENTRIFUGE_ROOT', __DIR__);
define('CENTRIFUGE_WEB_ROOT', CENTRIFUGE_ROOT . '/www');
define('CENTRIFUGE_APP_ROOT', CENTRIFUGE_ROOT . '/app');
define('CENTRIFUGE_MODELS_ROOT', CENTRIFUGE_APP_ROOT . '/models');
define('CENTRIFUGE_STATIC_ROOT', '/static');
define('CENTRIFUGE_PRODUCT_ROOT', '/static/products/');
define('CENTRIFUGE_LOG_ROOT', '/tmp/centrifuge.log');
define('CENTRIFUGE_CACHE_ROOT', '/tmp/centrifuge/');

require_once CENTRIFUGE_ROOT . '/vendor/autoload.php';

define('OBJ_TTL', 300);
define('PDO_URL', 'pgsql:host=localhost;dbname=rotator;port=5432;user=patrick');
define('CENTRIFUGE_LOG_LEVEL', Monolog\Logger::INFO);
define('CENTRIFUGE_ENV', 'dev');
define('FALLBACK_LANDER', 1);
