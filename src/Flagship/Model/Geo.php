<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;

use Punic\Territory;
use Punic\Language;
use Punic\Currency;
use Punic\Unit;
use Punic\Number;

class Geo {

    use ImmutableProperties;

    protected $id;
    protected $name;
    protected $country;
    protected $locale;
    protected $data = [];

    public function __construct(
        $id,
        $name,
        $country,
        $locale,
        $data = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->locale = $locale;
        $this->data = $data;

        $this->data['language.id'] = str_replace('_', '-', $this->locale);
    }

    public function pronoun($locale = '') {
        $lang = Language::getName($this->data['language.id']);
        $first = explode(' ', $lang)[0];
        return $first;
    }

    public function money($amount, $alt = 'narrow', $locale = '') {
        $code = Currency::getCurrencyForTerritory($this->country);
        $c = Currency::getSymbol($code, $alt, $locale);
        $num = Number::format($amount, 2, $this->locale);
        return $c . $num;
    }

    public function weight($amount, $alt = 'long', $locale = '') {
        $unit = $this->data['unit.weight'];
        return Unit::format($amount, $unit, $alt, $locale);
    }

}
