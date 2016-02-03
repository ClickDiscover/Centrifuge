<?php

namespace ClickDiscover\Interfaces;


class AbstractTransformer extends \League\Fractal\TransformerAbstract {
    public function transform (ValueInterface $v) {
        return $v->toArray();
    }
}
