<?php

namespace Flagship\Plates;


class LanderTemplate {

    public function __construct($assetRoot, $lander) {
        $this->steps = $lander->offers;
        $website = $lander->website;
        $namespace = $website->namespace;
        $this->file = $namespace . '::' . $website->templateFile;
        $this->assetRoot = $assetRoot . $namespace;
        $this->geo = $lander->geo;
        $this->variants = new VariantHtml($namespace, $lander->variants);
    }

    public function getFile() {
        return $this->file;
    }

    public function getData() {
        return array(
            'steps' => $this->steps,
            'geo' => $this->geo,
            'assets' => $this->assetRoot,
            'v' => $this->variants
        );
    }
}
