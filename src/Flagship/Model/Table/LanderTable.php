<?php

namespace Flagship\Model\Table;


class LanderTable {

    $namespace = "lander";

    public function fetch($id) {
        $sql = <<<SQL
            SELECT l.*, w.namespace, w.template_file, w.asset_dir FROM landers l
            INNER JOIN websites w ON (w.id = l.website_id)
            WHERE l.id = ?
SQL;
        return $sql;
    }
}
