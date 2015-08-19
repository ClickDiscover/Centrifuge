<?php

namespace Flagship\Model;


class Offer {

    protected $name;
    protected $stepNumber;
    protected $url;
    protected $imageUrl;

    public function __construct($stepNumber, $product, $imageUrl = false) {
        $this->name = $name;
        $this->stepNumber = $stepNumber;
        $this->imageUrl = $imageUrl;
    }
}

