<?php

namespace Flagship\Test;

class MockHasher {
    public function encode($arr) {
        return '[' . implode(',', $arr) . ']';
    }

    public function decode($str) {
        return explode(',', trim($str, '[]'));
    }
}
