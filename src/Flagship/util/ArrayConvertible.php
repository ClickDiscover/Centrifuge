<?php

namespace Flagship\Util;


interface ArrayConvertible {
    public function keys();
    public function toArray();
    public function fromArray(array $arr);
}


