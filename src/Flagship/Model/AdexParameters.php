<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;
use Flagship\Util\ArrayConversions;
use Flagship\Util\ArrayConvertible;



class AdexParameters implements ArrayConvertible {

    use ImmutableProperties;
    use ArrayConversions;

    protected $id;
    protected $name;
    protected $affiliateId;
    protected $vertical;
    protected $country;

    protected $__keyMap = [];

    public function __construct(
        $id = null,
        $name = null,
        $affiliateId = null,
        $vertical = null,
        $country = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->affiliateId = $affiliateId;
        $this->assetRoot = $vertical;
        $this->country = $country;
    }

    public function cleanName() {
        if(!isset($this->name)) {
            return implode(' | ', array(
                $this->affiliateId,
                $this->country,
                $this->vertical
            ));
        }
    }
}
