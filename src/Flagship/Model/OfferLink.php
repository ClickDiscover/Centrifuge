<?php

namespace Flagship\Model;


class OfferLink {

    protected $product;
    protected $stepNumber;
    protected $url;

    public function __construct($product, $stepNumber = 1, $url = false) {
        $this->product = $product;
        $this->stepNumber = $stepNumber;
        $this->url = $url;
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

    public function getUrl() {
        if($this->url) {
            return $this->url;
        }
    }

    public function setUrl($u) {
        $this->url = $u;
    }
}

