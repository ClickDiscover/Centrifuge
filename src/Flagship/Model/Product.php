<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;

class Product {

    use ImmutableProperties;

    protected $name;
    protected $imageUrl;

    public function __construct($name, $imageUrl = false) {
        $this->name = $name;
        $this->imageUrl = $imageUrl;
    }
}

