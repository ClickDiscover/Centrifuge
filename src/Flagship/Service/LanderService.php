<?php

namespace Flagship\Service;

use Flagship\Model\Lander;
use Flagship\Model\Website;

class LanderService {

    public $namespace = "lander";

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
}
