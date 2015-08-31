<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;

class OfferLink {

    use ImmutableProperties;

    protected $product;
    protected $stepNumber;
    protected $url;
    protected $source;
    protected $id;

    public function __construct($product, $stepNumber = 1, $url = false, $source = null, $id = null) {
        $this->product = $product;
        $this->stepNumber = $stepNumber;
        $this->url = $url;
        $this->id = $id;
        $this->source = $source;
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

