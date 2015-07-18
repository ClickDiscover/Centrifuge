<?php

require_once dirname(dirname(__DIR__)) . '/config.php';
require_once __DIR__ . "/product.php";
require_once __DIR__ . "/step.php";
require_once __DIR__ . "/tracking.php";
require_once CENTRIFUGE_APP_ROOT . "/util/iexception.php";
require_once CENTRIFUGE_APP_ROOT . "/util/variant.php";

class LanderNotFoundException extends CustomException {};


class VariantLanderHtml
{
    public $steps;
    public $namespace;
    public $templateFile;
    public $assetDirectory;
    public $rootPath;
    public $tracking;

    public function __construct($namespace, $rootPath, $templateFile, $assetDir, $variants, $steps, $tracking) {
        $this->namespace = $namespace;
        $this->templateFile = $templateFile;
        $this->assetDirectory = $assetDir;
        $this->steps = $steps;
        $this->rootPath = $rootPath;
        $this->tracking = $tracking;
        $this->variants = new VariantHtml($namespace, $variants);
    }

    public function getTemplate() {
        return $this->namespace . '::' . $this->templateFile;
    }

    public function toArray() {
        $assets = $this->rootPath . '/' . $this->namespace;

        return array(
            'steps' => $this->steps,
            'tracking' => $this->tracking,
            'assets' => $assets,
            'v' => $this->variants
        );
    }
}



class LanderFunctions
{
    public static function fetch($app, $id, $root = null) {
        $q = LanderFunctions::query($id);
        $res = $app->db->query($q)->fetch(PDO::FETCH_ASSOC);
        if ($res == false) {
            throw new LanderNotFoundException("Lander id: {$id} does not exist!", $id);
        }

        $tracking = Tracking::fromPGArray($res['tracking']);
        $products = [];

        if ($res['offer'] == 'adexchange') {
            $sql = "SELECT affiliate_id, vertical, country FROM ae_parameters WHERE id = ".$res['param_id'];
            $params = $app->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $products = AdExchangeProduct::fetchFromAdExchange($params['affiliate_id'], $params['vertical'], $params['country']);
        } elseif ($res['offer'] == 'network') {
            // $sql = "SELECT id, name, image_url FROM products WHERE id IN (".implode(',', [$res['product1_id'], $res['product2_id']]).")";
            $p1 = $app->db->query("SELECT id, name, image_url FROM products WHERE id = " . $res['product1_id'])->fetch(PDO::FETCH_ASSOC);
            $p2 = $app->db->query("SELECT id, name, image_url FROM products WHERE id = " . $res['product2_id'])->fetch(PDO::FETCH_ASSOC);
            $products[] = NetworkProduct::fromArray($p1, $app['PRODUCT_ROOT']);
            $products[] = NetworkProduct::fromArray($p2, $app['PRODUCT_ROOT']);
        }

        $steps = Step::fromProducts($products);
        $rootPath = isset($root) ? $root : $app['LANDER_ROOT'];
        $variants = json_decode($res['variants'], true);

        // $template = $res['namespace'] . '::' . $res['template_file'];
        // $assets   = $app['LANDER_ROOT'] . $res['namespace'] . '/' . $res['asset_dir'];
        // $assets = $app['LANDER_ROOT'] . $res['assets'];
        // return new LanderHtml($template, $assets, $steps, $tracking);
        return new VariantLanderHtml($res['namespace'], $rootPath, $res['template_file'], $res['asset_dir'], $variants, $steps, $tracking);
    }


    public static function query($id) {
        $sql = <<<SQL
SELECT l.*, w.namespace, w.template_file, w.asset_dir FROM landers l
INNER JOIN websites w ON (w.id = l.website_id)
WHERE l.id = {$id};
SQL;
        return $sql;
    }
}


