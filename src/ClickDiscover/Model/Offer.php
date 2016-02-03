<?php

namespace ClickDiscover\Model;


class Offer extends \ClickDiscover\Interfaces\ImmutableObject {

    protected $id;
    protected $product;
    protected $source = 'network';
    protected $vertical = 'diet'; // vertical
    protected $vendorName = null;

    // public function __construct(
        // $id,
        // $product,
        // $source = 'network',
        // $vertical = 'diet',
        // $vendorName = null
    // ) {
        // $this->id = $id;
        // $this->product = $product;
        // $this->source = $source;
        // $this->vertical = $vertical;
        // $this->vendorName = $vendorName;
    // }
}
