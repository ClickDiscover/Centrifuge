<?php

namespace Flagship\Util;

function from_camel_case($input) {
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }
    return implode('_', $ret);
}

function to_camel_case($in) {
    return preg_replace_callback('/_(.?)/', function($matches) {
             return ucfirst($matches[1]);
    }, $in);
}

trait ArrayConversions {

    // protected $__keyMap = [];


    private function __keys() {
        $vars = get_object_vars($this);
        $vars = array_filter($vars, function ($x) {
            return $x[0] !== '_';
        }, ARRAY_FILTER_USE_KEY);
        return $vars;
    }

    private function __renameKey($key, $toCamel) {
        $newKey = $key;
        if ($toCamel) {
            $inverse = array_flip($this->__keyMap);
            $newKey = array_key_exists($key, $inverse) ?  $inverse[$key] : $newKey;
            return to_camel_case($newKey);
        } else {
            $newKey = array_key_exists($key, $this->__keyMap) ?  $this->__keyMap[$key] : $newKey;
            return from_camel_case($newKey);
        }
    }

    public function keys($toCamel = true) {
        $vars = $this->__keys();
        return array_map(function ($x) use ($toCamel) {
            return (($toCamel) ? from_camel_case($x) : $x);
        }, array_keys($vars));
    }


    public function toArray() {
        $vars = $this->__keys();
        $new = [];
        foreach($vars as $key => $val) {
            $newKey = $this->__renameKey($key, false);
            $new[$newKey] = $val;
        }
        return $new;
    }

    public function fromArray(array $arr) {
        foreach ($arr as $key => $val) {
            $newKey = $this->__renameKey($key, true);
            $this->$newKey = $val;
        }
        return $this;
    }
}
