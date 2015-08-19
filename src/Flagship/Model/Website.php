<?php

namespace Flagship\Model;

use Flagship\Util\ImmutableProperties;
use Flagship\Util\ArrayConversions;

class Website {

    use ImmutableProperties;
    use ArrayConversions;

    protected $id;
    protected $name;
    protected $namespace;
    protected $assetRoot;

    protected $templateFile;

    protected $__keyMap = [
        'assetRoot' => 'asset_dir'
    ];

    public function __construct(
        $id = null,
        $name = null,
        $namespace = null,
        $assetRoot = null,
        $templateFile = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->namespace = $namespace;
        $this->assetRoot = $assetRoot;
        $this->templateFile = $templateFile;
    }
}
