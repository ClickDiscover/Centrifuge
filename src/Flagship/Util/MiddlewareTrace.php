<?php

namespace Flagship\Util;

trait MiddlewareTrace {
    public function call() {
        $clz = __CLASS__;
        var_dump("{$clz} call() : before next() <br>");
        parent::call();
        var_dump("{$clz} call() : after next() <br>");
    }
}
