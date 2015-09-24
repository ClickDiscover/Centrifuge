<?php

namespace Flagship\Util\Profiler;

use DebugBar\DataCollector\TimeDataCollector;
use Flagship\Util\Logger;

class DebugBarProfiler implements ProfilerInterface {

    public $timer;

    public function __construct(TimeDataCollector $timer, Logger $log, $constructTime = null) {

        $this->timer = $timer;
        $this->log = new Logger('Profiler', $log->getHandlers(), $log->getProcessors());

        // Fuck it. This is fair because this is a *DebugBar* profiler. DebugBar already uses _SERVER
        if (strpos($_SERVER['REQUEST_URI'], '_debugbar') != false) {
            $this->log->setHandlers([]);
        }
        $this->log->debug('Start time: ' . $timer->getRequestStartTime());
        if (isset($constructTime)) {
            $this->add('startup', $this->timer->getRequestStartTime(), $constructTime);
        }

        register_shutdown_function([$this, 'shutdown']);
    }

    public function shutdown() {
        $this->add('request.duration', $this->timer->getRequestStartTime(), microtime(true));
        $delta = 1000.0 * $this->timer->getRequestDuration();
        $this->log->debug('Shutdown. Duration: '. $delta .' ms');
    }

    public function start($name) {
        $this->timer->startMeasure($name);
    }

    public function stop($name) {
        $this->timer->stopMeasure($name);
        foreach ($this->timer->getMeasures() as $m) {
            if ($m['label'] === $name) {
                $this->logDelta($name, $m['start'], $m['end']);
            }
        }
    }

    public function add($name, $start, $end) {
        $this->timer->addMeasure($name, $start, $end);
        $this->logDelta($name, $start, $end);
    }

    public function sinceBeginning($name) {
        $this->add($name, $this->timer->getRequestStartTime(), microtime(true));
    }

    protected function logDelta($name, $start, $end) {
        $td = $end - $start;
        $sec = intval($td);
        $micro = $td - $sec;
        // $prettyMicro = strftime('%T', mktime(0, 0, $sec)) . str_replace('0.', '.', sprintf('%.3f', $micro));
        $ms = '' . number_format(1000.0 * $td, 4);
        $prettyMicro = str_pad($ms, 8, ' ', STR_PAD_LEFT) . ' ms ';

        $out  =  str_pad($name, 50);
        $out .= ' | ' . $prettyMicro;
        // $out .= '    ';
        // $out .= '[' . $start . ' - ' . $end . ']';
        $this->log->debug($out);
    }

}
