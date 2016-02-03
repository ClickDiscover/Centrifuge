<?php

namespace ClickDiscover\Interfaces;


abstract class AbstractObject implements ValueInterface {
    public function getHash() {
        return md5( serialize( $this ) );
    }

    public function equals( $target ) {
        if ( $this === $target ) {
            return true;
        }
        return is_object( $target )
            && get_called_class() === get_class( $target )
            && serialize( $this ) === serialize( $target );
    }


    public function getCopy () {
        $className = get_called_class();
        return new $className($this->toArray());
        // echo "Clone: ";
        // var_dump (get_object_vars($new));
        // echo PHP_EOL;
        // return $new->fromArray($this->toArray());
    }

    public function __clone() {
        return $this->getCopy();
    }

    public function __construct($arr = null) {
        if (isset($arr)) {
            $this->fromArray($arr);
        }
    }

    public function toArray() {
        $vars = get_object_vars($this);
        $vars = array_filter($vars, function ($x) {
            return $x[0] !== '_';
        }, ARRAY_FILTER_USE_KEY);
        $copy = [];
        foreach ($vars as $key => $val) {
            if ($val instanceof Arrayable) {
                $copy[$key] = $val->toArray();
            } else {
                $copy[$key] = $val;
            }
        }
        return $copy;
    }

    public function fromArray(array $arr) {
        foreach ($arr as $key => $val) {
            $this->$key = $val;
        }
        return $this;
    }
}
