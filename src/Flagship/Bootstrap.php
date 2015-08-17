<?php


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
