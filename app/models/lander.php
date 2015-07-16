<?php

require_once __DIR__ . "/product.php";
require_once __DIR__ . "/step.php";
require_once __DIR__ . "/tracking.php";


class LanderHtml
{
    public $steps;
    public $template;
    public $assetDirectory;
    public $tracking;

    public function __construct($template, $assetDir, $steps, $tracking) {
        $this->template = $template;
        $this->assetDirectory = $assetDir;
        $this->steps = $steps;
        $this->tracking = $tracking;
    }
}


class LanderFunctions
{
    public static function fetch($db, $id) {
        $q = LanderFunctions::query($id);
        $res = $db->query($q)->fetch(PDO::FETCH_ASSOC);
        $tracking = new Tracking($res['tracking_tags']);
        $products = [];

        if ($res['offer'] == 'adexchange') {
            $sql = "SELECT affiliate_id, vertical, country FROM ae_parameters WHERE id = ".$res['param_id'];
            $params = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $products = AdExchangeProduct::fetchFromAdExchange($params['affiliate_id'], $params['vertical'], $params['country']);
        } elseif ($res['offer'] == 'network') {
            $sql = "SELECT name, image_url FROM products WHERE id IN (".implode(',', [$res['product1_id'], $res['product2_id']]).")";
            foreach ($db->query($sql)->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $products[] = NetworkProduct::fromArray($row);
            }
        }

        $steps = Step::fromProducts($products);
        $template = substr($res['template'], 0, -4);
        $assets = $res['assets'];
        // print_r($template)."<br>";
        // print_r($assets)."<br>";
        // print_r($steps)."<br>";
        return new LanderHtml($template, $assets, $steps, $tracking);
    }


    public static function query($id) {
        $sql = <<<SQL
SELECT l.*, w.template, w.assets FROM landers l
INNER JOIN websites w ON (w.id = l.website_id)
WHERE l.id = {$id};
SQL;
        return $sql;
    }
}
