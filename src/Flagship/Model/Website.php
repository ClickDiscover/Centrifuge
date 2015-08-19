<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;

class Website {

    use ImmutableProperties;

    protected $id;
    protected $name;
    protected $namespace;
    protected $assetRoot;

    protected $templateFile;
    protected $config;

    public function __construct(
        $id,
        $name,
        $namespace,
        $assetRoot,
        $templateFile
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->namespace = $namespace;
        $this->assetRoot = $assetRoot;
        $this->templateFile = $templateFile;
        $this->config = [];
    }
}
