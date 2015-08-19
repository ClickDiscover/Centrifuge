<?php

namespace Flagship\Service;


class LanderService {

    public $namespace = "lander";

    protected $db;
    protected $offers;

    public function __construct($db, $offers) {
        $this->db = $db;
        $this->offers = $offers;
    }

    public function fetch($id) {
        $sql = <<<SQL
            SELECT l.*, w.namespace, w.template_file, w.asset_dir FROM landers l
            INNER JOIN websites w ON (w.id = l.website_id)
            WHERE l.id = ?
SQL;

        $row = $this->db->fetch($this->namespace, $id, $sql);
        $offers = $this->offers->fetch(
            $row['offer'],
            $row['param_id'],
            $row['product1_id'],
            $row['product2_id']
        );
        return $offers;
    }

}
