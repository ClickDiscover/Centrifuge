<?php

namespace ClickDiscover\Interfaces;


trait ImmutableProperties{
    public function __get($method) {
        $properties = array_keys(get_object_vars($this));
        if (in_array($method, $properties)) {
            return $this->$method;
        } else {
           throw new \InvalidArgumentException("$method is not a property of ".get_called_class());
        }
    }

    public function __call ($name, $arguments) {
        if (strrpos($name, 'with', -strlen($name)) !== false) {
            $property = lcfirst( substr($name, 4) );

            if ($this->__get($property) !== false) {
                $clone = clone $this;
                $clone->$property = $arguments[0];
                return $clone;
            }
        } else {

            throw new \InvalidArgumentException("$name is not valid method of ".get_called_class());
        }
    }
}
