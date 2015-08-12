<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';


class LibratoMetrics {
    protected $statsd;
    protected $source;
    protected $metric;

    public function __construct(&$statsd, $source, $metric) {
        $this->statsd = $statsd;
        $this->metric = $metric;
        $this->source = $source;
    }

    public function name($s, $m) {
        $s = implode('.', $s);
        $m = implode('.', $m);
        return $s . '-' . $m;
    }

    private function push($raw, $value) {
        if (isset($value)) {
            $this->statsd->gauge($raw, $value);
        } else {
            $this->statsd->increment($raw);
        }
    }

    public function totalName ($name) {
        return $this->name($this->source, array_merge($this->metric, [$name]));
    }

    public function breakoutName ($type, $id, $name) {
        return $this->name(
            array_merge($this->source, [$type, $id]),
            array_merge($this->metric, [$type, $name])
        );
    }

    public function total ($name, $value = null) {
        return $this->push($this->totalName($name), $value);
    }

    public function breakout ($type, $id, $name, $value = null) {
        return $this->push($this->breakoutName($type, $id, $name), $value);
    }

}
