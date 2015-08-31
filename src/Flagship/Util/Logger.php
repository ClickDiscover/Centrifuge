<?php
namespace Flagship\Util;
use Slim\Log as SlimLog;
use Monolog\Logger as Monolog;

class Logger extends Monolog {

    private static $instance;

    public static function getInstance() {
        return static::$instance;
    }

    public static function setInstance($i) {
        static::$instance = $i;
    }



    protected $slimToMonolog = array(
        SlimLog::EMERGENCY => Monolog::EMERGENCY,
        SlimLog::ALERT     => Monolog::ALERT,
        SlimLog::CRITICAL  => Monolog::CRITICAL,
        SlimLog::FATAL     => Monolog::CRITICAL,
        SlimLog::ERROR     => Monolog::ERROR,
        SlimLog::WARN      => Monolog::WARNING,
        SlimLog::NOTICE    => Monolog::NOTICE,
        SlimLog::INFO      => Monolog::INFO,
        SlimLog::DEBUG     => Monolog::DEBUG
    );



    function write($message, $level) {
        $this->addRecord($this->slimToMonolog[$level], $message);
    }
}

