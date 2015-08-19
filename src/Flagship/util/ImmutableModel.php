<?php

namespace Flagship\Util;


class ImmutableModel {

    private $__properties;

    public function __construct($options = []) {
        $this->__properties = array_keys(get_object_vars($this));
        foreach ($this->__properties as $p) {
            if (array_key_exists($p, $options)) {
                $this->$p = $options[$p];
            }
        }
    }

    public function __get($method) {
        if (array_key_exists($method, $this->__properties)) {
            return $this->__properties[$method];
        }
        return false;
    }

}

