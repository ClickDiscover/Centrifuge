<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;
use Flagship\Util\ArrayConversions;
use Flagship\Util\ArrayConvertible;


class Product implements ArrayConvertible {

    use ImmutableProperties;
    use ArrayConversions;

    protected $id;
    protected $name;
    protected $imageUrl;
    protected $source;
    protected $vertical;
    protected $__keyMap = [];

    public function __construct($id, $name, $imageUrl = null, $source = null, $vertical = null) {
        $this->id = $id;
        $this->name = $name;
        $this->imageUrl = $imageUrl;
        $this->source = $source;
        $this->vertical = $vertical;
    }
}

