<?php

namespace ClickDiscover\Model;

use ClickDiscover\Interfaces\ImmutableObject;

class Creative extends ImmutableObject {

    protected $id;
    protected $text;
    protected $image;
    protected $cta;
}
