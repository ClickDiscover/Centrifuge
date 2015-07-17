<?php

require_once dirname(dirname(__DIR__)) . '/config.php';
require BULLET_ROOT . '/vendor/autoload.php';
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class VariantExtension implements ExtensionInterface
{
    public $template;
    protected $engine;
    protected $namespace;

    public function __construct($namespace) {
        $this->namespace = $namespace;
    }

    public function register(Engine $engine) {
        $this->engine = $engine;
        $engine->registerFunction('variant', [$this, 'variant']);
    }

    public function variant($kind, $item = null) {
        $path = $this->namespace . '::variants/' . $kind . '/';
        if (isset($item)) {
            $path .= $item;
        } else {
            $path .= 'default';
        }

        return $this->engine->render($path);
    }
}
