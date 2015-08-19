<?php

namespace Flagship\Util;


trait ImmutableProperties{
    public function __get($method) {
        $properties = array_keys(get_object_vars($this));
        if (in_array($method, $properties)) {
            return $this->$method;
        }
        return false;
    }
}

