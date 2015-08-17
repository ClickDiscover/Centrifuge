<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';

use League\Event\ListenerInterface;
use League\Event\EventInterface;


class TotalsListener implements ListenerInterface {

    public function isListener($listener) {
        return $listener === $this;
    }

    public function handle (EventInterface $event, $params = null) {
        echo "Events" . PHP_EOL;
        echo  $event->getName() . PHP_EOL;
        print_r($params);
    }
}
