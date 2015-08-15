<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';

use League\Event\ListenerInterface;
use League\Event\EventInterface;


class CentrifugeListener extends AbstractEvent {
    public function handle ($event, $params = null) {
    }
}
