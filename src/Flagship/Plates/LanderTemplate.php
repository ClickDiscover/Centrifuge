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
    }

    public function getFile() {
        return $this->file;
    }

    public function getData($staticRoot = '') {
        return array(
            'steps' => $this->steps,
            'tracking' => $this->tracking,
            'assets' => $this->assetRoot,
            'v' => []
        );
    }
}
