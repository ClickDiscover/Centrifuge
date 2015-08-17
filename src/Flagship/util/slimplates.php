<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require_once CENTRIFUGE_ROOT . '/vendor/autoload.php';
require_once CENTRIFUGE_APP_ROOT . '/util/variant.php';
require_once CENTRIFUGE_APP_ROOT . '/util/html.php';

use League\Plates\Engine;
use Slim\View;


class PlatesView extends View {

    private $engine;

    public function __construct($plates) {
        parent::__construct();
        $this->engine = $plates;
    }

    public function render($template, $data = null) {
        $data = isset($data) ? $data : [];
        $allData = array_merge($this->all(), $data);
        return $this->engine->render($template, $allData);
    }

    public static function fromConfig($config) {
        $templateRoot = $config['templates.path'] . $config['paths']['relative_landers'];

        $plates = new League\Plates\Engine($templateRoot);
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

