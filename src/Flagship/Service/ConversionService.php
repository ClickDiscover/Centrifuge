<?php

namespace Flagship\Service;


class ConversionService {

    protected $namespace = 'conversions';
    protected $redis;

    public function __construct($redis) {
        $this->redis = $redis;
    }


    public function totals() {
        return $this->redis->get('interceptor:conversions:totals');
    }

    public function keywords() {
        $keys = $this->redis->keys('interceptor:conversions:keywords:*');

        $data = array();
        foreach ($keys as $k) {
            $keyword = array_reverse(explode(':', $k))[0];
            $data[$keyword] = $this->redis->get($k);
        }
        return $data;
    }
}
