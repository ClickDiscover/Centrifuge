<?php

trait Identifiable {
    public $id = null;

    function getId() {
        return $this->id;
    }
}
