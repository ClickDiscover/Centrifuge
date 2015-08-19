<?php

namespace Flagship\Plates;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class VariantExtension implements ExtensionInterface
{
    // public $template;
    protected $engine;

    public function register(Engine $engine) {
        $engine->registerFunction('variant', [$this, 'nothing']);
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

class VariantHtml {
    protected $namespace;
    protected $variants;

    public function __construct($namespace, $variants) {
        $this->namespace = $namespace;
        $this->variants = $variants;
    }

    public function getName($kind, $default='default') {
        return isset($this->variants[$kind]) ? $this->variants[$kind] : $default;
    }

    public function get($kind, $override = null) {
        $path = $this->namespace . '::variants/' . $kind . '/';
        if (isset($override)) {
            $path .= $override;
        } else {
            $path .= $this->getName($kind);
        }
        return $path;
    }
}
