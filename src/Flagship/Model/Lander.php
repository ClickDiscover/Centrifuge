<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;
use Flagship\Plates\LanderTemplate;

class Lander {

    use ImmutableProperties;

    protected $id;
    protected $website;
    protected $offers;
    protected $variants;
    protected $notes;

    // For Intl landers to user their native currency
    protected $locale = 'US';

    public function __construct(
        $id,
        $website,
        $offers,
        $variants,
        $notes
    ) {
        $this->id = $id;
        $this->website = $website;
        $this->offers = $offers;
        $this->variants = $variants;
        $this->notes = $notes;
    }

}
