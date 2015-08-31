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
        return new LanderTemplate($this->assetRoot, $lander);
    }

    public function landerRender($app, $lander) {
        $template = $this->landerTemplate($lander);
        $data = $template->getData();
        if ($this->has('scripts')) {
            $this->set('scripts', implode('\n', $this->get('scripts')));
        }
        $steps = $data['steps'];
        $app->view->replace([
            'step1_name'  => $steps[1]->getName(),
            'step1_image' => $steps[1]->getImageUrl(),
            'step1_link'  => $steps[1]->getUrl(),
            'step2_name'  => $steps[2]->getName(),
            'step2_image' => $steps[2]->getImageUrl(),
            'step2_link'  => $steps[2]->getUrl()
        ]);

        return $app->render($template->getFile(), $data);
    }
}

