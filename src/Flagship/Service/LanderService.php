<?php

namespace Flagship\Service;

use Flagship\Model\Lander;
use Flagship\Model\Website;

class LanderService {

    public $namespace = "landers";

    protected $db;
    protected $offers;

    public function __construct($db, $offers) {
        $this->db = $db;
        $this->offers = $offers;
    }

    public function fetch($id) {
        $row = $this->db->fetch($this->namespace, $id, self::SQL_SELECT);
        return $this->fromRow($row);
    }

    public function fromRow($row) {
        $website = $this->websiteFromArray($row);
        $offers = $this->offers->fetch(
            $row['offer'],
            $row['param_id'],
            $row['product1_id'],
            $row['product2_id']
        );
        $variants = json_decode($row['variants'], true);

        return new Lander(
            $row['id'],
            $website,
            $offers,
            $variants,
            $row['notes']
        );
    }

    public function websiteFromArray($res) {
        return new Website(
            $res['website_id'],
            $res['website_name'],
            $res['namespace'],
            $res['asset_dir'],
            $res['template_file']
        );
    }

    const SQL_BASE = <<<SQL
        SELECT l.*, w.id AS website_id, w.name AS website_name, w.namespace, w.template_file, w.asset_dir FROM landers l
        INNER JOIN websites w ON (w.id = l.website_id)
SQL;

    const SQL_SELECT = self::SQL_BASE . " WHERE l.id = ?";

    const SQL_SELECT_ALL = self::SQL_BASE . " ORDER BY id DESC";

    public function fetchAll() {
        $rows = $this->db->uncachedFetchAll(self::SQL_SELECT_ALL);
        return array_map(function ($x) {
            return $this->fromRow($x);
        }, $rows);
    }

    public function fetchAllWebsites() {
        $sql = "SELECT id AS website_id, name AS website_name, namespace, template_file, asset_dir FROM websites";
        $rows = $this->db->uncachedFetchAll($sql);
        return array_map(function ($x) {
            return $this->websiteFromArray($x);
        }, $rows);
    }

    public function insert($arr) {
        // Remove data from different offers
        if ($arr['notes'] === '') {
            unset($arr['notes']);
        }
        if (isset($arr['tracking'])) {
            unset($arr['tracking']);
        }

        if (isset($arr['variants'])) {
            $vars = array_flip($arr['variants']);
            unset($vars['default']);
            if (count($vars) > 0) {
                $arr['variants'] = json_encode(array_flip($vars));
            } else {
                unset($arr['variants']);
            }
        }

        if($arr['offer'] == 'adexchange') {
            unset($arr['product1_id']);
            unset($arr['product2_id']);
        } elseif ($arr['offer'] == 'network') {
            unset($arr['param_id']);
        }
        return $this->db->insertArray($this->namespace, $arr);
    }

}
