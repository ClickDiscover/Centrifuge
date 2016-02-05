<?php

namespace ClickDiscover\Model;

use ClickDiscover\Interfaces\ImmutableObject;


class Product extends ImmutableObject {

    protected $id;
    protected $name;
    protected $image;

    protected $description = null;
    protected $category = null;
    protected $vendorName = null;
}
