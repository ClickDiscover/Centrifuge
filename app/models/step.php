<?php

require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';
use League\Url\Url;


class Step
{
    protected $id;
    protected $p;
    protected $baseUri = 'click';
    protected $landerId;

    public function __construct($id, $product, $landerId = null) {
        $this->id = $id;
        $this->p = $product;
        $this->landerId = $landerId;
    }

    public static function fromProducts($products, $landerId = null) {
        $c = 1;
        $steps = array();
        foreach ($products as $r) {
            $steps[$c] = new Step($c, $r, $landerId);
            $c++;
        }
        return $steps;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->p->getName();
    }

    public function getUrl() {
        $url = Url::createFromServer($_SERVER);
        $url->setPath($this->baseUri);
        $url->getPath()->append("" . $this->id);

        if (isset($this->landerId)) {
            $url->getQuery()->modify(array('lander' => $this->landerId));
        }
        return $url;
    }

    public function getImageUrl() {
        return $this->p->getImageUrl();
    }
}
