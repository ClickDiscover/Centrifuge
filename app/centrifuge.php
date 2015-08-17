<?php
require_once dirname(__DIR__) . '/config.php';
require_once CENTRIFUGE_ROOT . '/vendor/autoload.php';
require_once CENTRIFUGE_APP_ROOT . '/util/slimplates.php';



class Centrifuge {

    public $log;
    public $db;
    public $cacheDriver;
    public $statsd;
    public $view;

    public $context;
    public $cookies;
    public $events;
    public $config;

    public function __construct($config) {
        $this->db = new F3\LazyPDO\LazyPDO($config['database']['pdo']);

        $this->cacheDriver = new Stash\Driver\FileSystem();
        $this->cacheDriver->setOptions(array('path' => CENTRIFUGE_CACHE_ROOT));

        $this->statsd = new \Domnikl\Statsd\Connection\UdpSocket('localhost', 8125);

        // $this->setupLogging($config);
        $this->config = $config;
    }


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

    public function instrumentSlim(&$app) {
        $app->log->setWriter(self::setupSlimMonolog($this->config));
        $app->view(PlatesView::fromConfig($this->config));
        $app->container->set('db', $this->db);
    }
}
