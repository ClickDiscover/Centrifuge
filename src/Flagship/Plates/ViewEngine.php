<?php

namespace Flagship\Plates;

use League\Plates\Engine;
use \Slim\View as SlimView;

use Flagship\Plates\LanderTemplate;

class ViewEngine extends SlimView {

    private $engine;
    private $assetRoot;

    public function __construct($plates, $assetRoot) {
        parent::__construct();
        $this->engine = $plates;
        $this->assetRoot = $assetRoot;
    }

    public function render($template, $data = null) {
        $data = isset($data) ? $data : [];
        $allData = array_merge($this->all(), $data);
        return $this->engine->render($template, $allData);
    }

    public function addFolder($namespace, $folder) {
        $this->engine->addFolder($namespace, $folder);
    }

    public function landerTemplate($lander) {
        $folders = $this->engine->getFolders();
        $namespace = $lander->website->namespace;
        if (!$folders->exists($namespace)) {
            $this->engine->addFolder($namespace, $this->engine->getDirectory() . $namespace);
        }
        $template = new LanderTemplate($this->assetRoot, $lander);
        $steps = $template->getData()['steps'];
        foreach ($steps as $idx => $step) {
            $this->set("step{$idx}_name", $step->getName());
            $this->set("step{$idx}_image", $step->getImageUrl());
            $this->set("step{$idx}_link", $step->getUrl());
        }
        $this->set('debug_lander', $lander);
        return $template;
    }

    public function landerRender($app, $lander) {
        $template = $this->landerTemplate($lander);
        if ($this->has('scripts')) {
            $this->set('scripts', implode('\n', $this->get('scripts')));
        }
        return $app->render($template->getFile(), $template->getData());
    }

    public function make($name) {
        return $this->engine->make($name);
    }
}

