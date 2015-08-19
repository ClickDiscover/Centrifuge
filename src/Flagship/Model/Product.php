<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableModel;

class Product extends ImmutableModel {
    protected $name;
    protected $imageUrl;

    public function __construct($name, $imageUrl = false) {
        parent::__construct();
        $this->name = $name;
        $this->imageUrl = $imageUrl;
    }
}

