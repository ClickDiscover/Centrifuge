<?php

namespace ClickDiscover\Model;


use ClickDiscover\Interfaces\ImmutableObject;


class PostDto extends ImmutableObject {

    protected $id;
    protected $title;
    protected $author;
    protected $category;
    protected $description;
}
