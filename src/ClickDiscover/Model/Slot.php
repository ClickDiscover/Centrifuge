<?php

namespace ClickDiscover\Model;

use ClickDiscover\Interfaces\ImmutableObject;

class Slot extends ImmutableObject {

    protected $id;
    protected $article;
    protected $type;
    protected $enabled;
}
