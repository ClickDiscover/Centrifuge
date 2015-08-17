<?php

namespace Flagship\Service;


class LanderService {

    public $namespace = "lander";

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetch($id) {
        $sql = <<<SQL
            SELECT l.*, w.namespace, w.template_file, w.asset_dir FROM landers l
            INNER JOIN websites w ON (w.id = l.website_id)
            WHERE l.id = ?
SQL;

        $row = $db->fetch($this->namespace, $id, $sql));
    }

}
