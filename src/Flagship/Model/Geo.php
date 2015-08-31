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

        if (empty($this->data['money.width'])) {
            $this->data['money.width'] = 'narrow';
        }

        if (empty($this->data['unit.format'])) {
            $this->data['unit.format'] = 'short';
        }
    }

    public function pronoun($locale = 'en') {
        if (empty($this->data['pronoun'])) {
            $loc = ($locale != "") ? $locale : $this->locale;
            $lang = Language::getName($this->data['language.id'], $loc);
            $first = explode(' ', $lang)[0];
            return $first;
        } else {
            return $this->data['pronoun'];
        }
    }

    public function money($amount, $width = '', $locale = 'en') {
        $wid  = ($width != "")  ? $width  : $this->data['money.width'];
        $loc  = ($locale != "") ? $locale : $this->locale;
        $code = Currency::getCurrencyForTerritory($this->country);
        $c    = Currency::getSymbol($code, $wid, $loc);
        $num  = Number::format($amount, 2, $loc);
        return $c . $num;
    }

    public function weight($amount, $alt = '', $locale = 'en') {
        $unit = $this->data['unit.weight'];
        $fmt  = ($alt != '') ? $alt : $this->data['unit.format'];
        $loc  = ($locale != "") ? $locale : $this->locale;
        return Unit::format($amount, $unit, $fmt, $loc);
    }
}
