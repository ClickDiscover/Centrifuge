<?php
require_once dirname(__DIR__) . '/c.php';
require_once CENTRIFUGE_ROOT . '/vendor/autoload.php';



class Container extends Pimple {

    public function __construct(array $config) {
        parent::__construct();
        $this['config'] = $config;
        $this->configure();
    }


    public function configure() {
        $c = $this;
        // Database
        $this['db'] = function () use ($c) {
            return new PDO($c['config']['database']['pdo'], null, null, array(
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ));
        };

        // Cache
        $this['cacheDriver'] = function () use ($c) {
            $driver = new Stash\Driver\FileSystem();
            $driver->setOptions(array('path' => $c['config']['cache']['root']));
            return $driver;
        }
        $this['cache'] = function () use ($c) {
            $cache = new Stash\Pool($this['cacheDriver']);
            $cache->setNamespace($c['config']['name']);
            return $cache;
        }

        // Session
        $this['sessionCache'] = function () use ($c) {
            $sessionCache= new Stash\Pool($this['cacheDriver']);
            $sessionCache->setNamespace('session');
            // Session::registerHandler(new Stash\Session($sessionCache));
            return $sessionCache;
        }

        // Statsd
        $this['statsd'] = function () use ($c) {
            $conn = new \Domnikl\Statsd\Connection\UdpSocket('localhost', 8125);
            return new \Domnikl\Statds\Client($conn);
        }
    }

}
