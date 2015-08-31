<?php

namespace Flagship\Plates;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class VariantExtension implements ExtensionInterface {
    // public $template;
    protected $engine;

    public function register(Engine $engine) {
        $engine->registerFunction('variant', [$this, 'variant']);
        $this->engine = $engine;
    }

    public function nothing() {
        return '';
    }

    public function variant($variants, $kind, $item = null) {
        $path = $variants->get($kind, $item);
        if ($this->engine->exists($path)) {
            return $this->engine->render($path);
        }
    }
}
