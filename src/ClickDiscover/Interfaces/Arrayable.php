<?php

namespace ClickDiscover\Interfaces;


interface Arrayable {
    public function toArray();
    public function fromArray(array $arr);
}
