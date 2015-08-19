<?php

namespace Flagship\Model;


class Product {
    protected $name;
    protected $imageUrl;

    public function __construct($name, $imageUrl = false) {
        $this->name = $name;
        $this->imageUrl = $imageUrl;
    }
}

