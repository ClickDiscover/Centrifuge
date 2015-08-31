<?php

namespace Flagship\Plates;


class LanderTemplate {

    public function __construct($assetRoot, $lander) {
        $this->steps = $lander->offers;
        $website = $lander->website;
        $namespace = $website->namespace;
        $this->file = $namespace . '::' . $website->templateFile;
        $this->assetRoot = $assetRoot . $namespace;
        $this->tracking = [];
        $this->variants = new VariantHtml($namespace, $lander->variants);
    }

    public function getFile() {
        return $this->file;
    }

    public function getData() {
        return array(
            'steps' => $this->steps,
            'tracking' => $this->tracking,
            'assets' => $this->assetRoot,
            'v' => $this->variants
        );
    }
}
