<?php

namespace Flagship\Model;


class OfferLink {

    protected $product;
    protected $stepNumber;

    public function __construct($product, $stepNumber = 1) {
        $this->product = $product;
        $this->stepNumber = $stepNumber;
    }

    public function getStepNumber() {
        return $this->stepNumber;
    }

    public function getName() {
        return $this->product->name;
    }

    public function getImageUrl() {
        return $this->product->imageUrl;
    }
}

