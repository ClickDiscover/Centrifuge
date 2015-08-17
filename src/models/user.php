<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';

class User {
    protected $id;
    protected $sessionId;
    protected $agent;

    public function __construct($id) {
        $this->id = $id;
    }

    public static function fromRequest($req) {
    }

}
