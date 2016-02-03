<?php

namespace ClickDiscover\Model;


class Product extends \ClickDiscover\Interfaces\ImmutableObject {

    protected $id;
    protected $name;
    protected $image;

    protected $description = null;
    protected $category = null;
    protected $vendorName = null;
}
