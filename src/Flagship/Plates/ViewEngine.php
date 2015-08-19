<?php

namespace Flagship\Plates;

use League\Plates\Engine;
use \Slim\View as SlimView;


class ViewEngine extends SlimView {

    private $engine;

    public function __construct($plates) {
        parent::__construct();
        $this->engine = $plates;
    }

    public function render($template, $data = null) {
        $data = isset($data) ? $data : [];
        $allData = array_merge($this->all(), $data);
        echo "All ";
        var_dump(array_keys($allData));
        return $this->engine->render($template, $allData);
    }

    public static function fromConfig($config) {
        $templateRoot = $config['templates.path'] . $config['paths']['relative_landers'];

        $plates = new Engine($templateRoot);
        $plates->loadExtension(new VariantExtension);
        $plates->loadExtension(new HtmlExtension);
        $view = new PlatesView($plates);
        return $view->addFolder('admin', $config['paths']['template'] . '/admin');
    }

    public function addFolder($namespace, $folder) {
        $this->engine->addFolder($namespace, $folder);
        return $this;
    }
}

