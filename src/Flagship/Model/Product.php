<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;

class Product {

    use ImmutableProperties;

    protected $id;
    protected $name;
    protected $imageUrl;
    protected $source;

    public function __construct($id, $name, $imageUrl = null, $source = null) {
        $this->id = $id;
        $this->name = $name;
        $this->imageUrl = $imageUrl;
        $this->source = $source;
    }
}

