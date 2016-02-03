<?php

namespace ClickDiscover\Model;

use ClickDiscover\Interfaces\ImmutableObject;

class AdSlot extends ImmutableObject {

    protected $id;
    protected $product;
    protected $text;
    protected $image;
    protected $cta;
}
