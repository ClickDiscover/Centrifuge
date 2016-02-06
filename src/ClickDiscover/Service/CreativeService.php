<?php

namespace ClickDiscover\Service;

use ClickDiscover\Model\Creative;


class CreativeService {

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetchCreative($id) {
        
        $arr = $this->db->fetch('creatives', $id, $this->sqlFor('creatives'));
        $text = $this->db->fetch('ad_text', $arr['text_id'], $this->sqlFor('ad_text'));
        $image = $this->db->fetch('ad_image', $arr['image_id'], $this->sqlFor('ad_image'));
        $cta = $this->db->fetch('ad_cta', $arr['cta_id'], $this->sqlFor('ad_cta'));

        $creative = new Creative;
        $creative->fromArray([
            'id' => $id,
            'text' => $text,
            'image' => $image,
            'cta' => $cta,
        ]);
        return $creative;
    }

    protected function sqlFor($table) {
        return "SELECT * FROM $table WHERE id = ?";
    }

    public function fetchRandomCreative() {
        $image = $this->selectRandom('ad_image');
        $text = $this->selectRandom('ad_text');
        $cta = $this->selectRandom('ad_cta');

        $creative = new Creative;
        $creative->fromArray([
            'text' => $text,
            'image' => $image,
            'cta' => $cta,
        ]);
        return $creative;
    }

    protected function selectRandom($table) {
        $query = "SELECT * FROM $table ORDER BY random() LIMIT 1";
        $stmt = $this->db->db->query($query);
        return $stmt->fetch();
    }
}
