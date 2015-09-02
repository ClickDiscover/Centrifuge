<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;
use Flagship\Util\ArrayConversions;
use Flagship\Util\ArrayConvertible;

use Punic\Territory;
use Punic\Language;
use Punic\Currency;
use Punic\Unit;
use Punic\Number;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;


class Geo {

    use ImmutableProperties;

    protected $id;
    protected $name;
    protected $country;
    protected $locale;
    protected $data = [];
    protected $variables = [];

    public function __construct(
        $id,
        $name,
        $country,
        $locale,
        $data = [],
        $variables = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->locale = $locale;
        $this->data = $data;
        $this->variables = $variables;

        $this->data['language.id'] = str_replace('_', '-', $this->locale);

        if (empty($this->data['money.width'])) {
            $this->data['money.width'] = 'narrow';
        }

        if (empty($this->data['unit.format'])) {
            $this->data['unit.format'] = 'short,1';
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

    public function money($amount, $precision = 2, $width = '', $locale = 'en') {
        $wid  = ($width != "")  ? $width  : $this->data['money.width'];
        $loc  = ($locale != "") ? $locale : $this->locale;
        $code = Currency::getCurrencyForTerritory($this->country);
        $c    = Currency::getSymbol($code, $wid, $loc);
        $num  = Number::format($amount, $precision, $loc);
        return $c . $num;
    }

    public function weight($amount, $unit = 'pound', $alt = '', $locale = 'en') {
        $mass = new Mass($amount, $unit);
        $unit = $this->data['unit.weight'];
        $value = $mass->toUnit($unit);
        $fmt  = ($alt != '') ? $alt : $this->data['unit.format'];
        $loc  = ($locale != "") ? $locale : $this->locale;
        return Unit::format($value, $unit, $fmt, $loc);
    }

    public function length($amount, $unit, $alt = '', $locale = 'en') {
        $l = new Length($amount, $unit);
        $unit = $this->data['unit.length'];
        $value = $l->toUnit($unit);
        $fmt  = ($alt != '') ? $alt : $this->data['unit.format'];
        $loc  = ($locale != "") ? $locale : $this->locale;
        return Unit::format($value, $unit, $fmt, $loc);
    }

    public function variable($key, $default = '') {
        if (empty($this->variables[$key])) {
            return $default;
        }
        return $this->variables[$key];
    }

    public function v($key, $override = null) {
        return $this->variable($key, $override);
    }


    public function toArray() {
        return [
            'ID' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'locale' => $this->locale,
            'Weight Example' => $this->weight(25.3),
            'Money Example' => $this->money(100),
            'Pronoun' => $this->pronoun(),
            'Weight Unit' => $this->data['unit.weight'],
            'Unit Format' => $this->data['unit.format'],
            'Money Width' => $this->data['money.width'],
            'Variables' => print_r($this->variables, true)
        ];
    }

}
