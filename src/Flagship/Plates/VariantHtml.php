<?php

namespace Flagship\Plates;


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
