<?php
require_once dirname(__DIR__) . '/config.php';
require_once CENTRIFUGE_ROOT . '/vendor/autoload.php';
require_once CENTRIFUGE_APP_ROOT . '/util/slimplates.php';



class DataLayer {

    public $log;
    public $view;
    public $context;

    public $cookies;
    public $config;

    public $cacheDriver;
    public $statsdSocket;
    public $db;
    public $statsd;
    public $events;

    public function __construct($config) {
        $this->config = $config;

        // Database
        $this->db = new F3\LazyPDO\LazyPDO($config['database']['pdo'], null, null, array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ));

        // Cache
        $this->cacheDriver = new Stash\Driver\FileSystem();
        $this->cacheDriver->setOptions(array('path' => $config['cache']['root']));
        $cache = new Stash\Pool($this->cacheDriver);
        $cache->setNamespace($config['name']);
        // Session
        $sessionCache= new Stash\Pool($this->cacheDriver);
        $sessionCache->setNamespace('session');
        // Session::registerHandler(new Stash\Session($sessionCache));


        // Statsd
        $this->statsdSocket = new \Domnikl\Statsd\Connection\UdpSocket('localhost', 8125);
        $this->statsd = new \Domnikl\Statds\Client($connection);
    }

}

class SlimBootstrap {

    protected static function setupSlimMonolog($config) {
        $logConfig = array(
            'name' => $config['name'],
            'handlers' => array(
                new Monolog\Handler\StreamHandler(
                    $config['logging']['root'] . 'centrifuge.log',
                    Monolog\Logger::toMonologLevel($config['logging']['root'])
                )
            ),
            'processors' => array(
                new Monolog\Processor\WebProcessor,
                new Monolog\Processor\MemoryUsageProcessor
            )
        );
        return new \Flynsarmy\SlimMonolog\Log\MonologWriter($logConfig);
    }

    // protected static function addCustomUrls($app)

    public static function instrumentSlim(&$app) {
        $app->log->setWriter(self::setupSlimMonolog($this->config));
        $app->view(PlatesView::fromConfig($this->config));
        $app->container->set('db', $this->db);
    }
}
