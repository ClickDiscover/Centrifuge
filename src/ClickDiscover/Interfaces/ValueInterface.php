<?php

namespace ClickDiscover\Interfaces;


/***************************************
 * ValueInterface expects:
 *   public function getHash()
 *   public function toArray()
 *   public function fromArray()
 *   public function equals()
 *   public function getCopy()
 ***************************************/


interface ValueInterface extends Hashable, Arrayable, Comparable, Cloneable {
}

