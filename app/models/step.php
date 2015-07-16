<?php

require_once dirname(dirname(__DIR__)) . '/config.php';
require BULLET_ROOT . '/vendor/autoload.php';
use League\Url\Url;


class Step
{
    protected $id;
    protected $p;
    protected $baseUri = 'base2.php';

    public function __construct($id, $product)
    {
        $this->id = $id;
        $this->p = $product;
    }

    public static function fromProducts($products) {
        $c = 1;
        $steps = array();
        foreach ($products as $r) {
            $steps[$c] = new Step($c, $r);
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
        $url->getQuery()->modify(array("id" => $this->id));
        return $url;
    }

    public function getImageUrl() {
        return $this->p->getImageUrl();
    }
}
