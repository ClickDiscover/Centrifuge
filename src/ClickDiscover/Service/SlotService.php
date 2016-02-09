<?php

namespace ClickDiscover\Service;

use ClickDiscover\Model\Slot;


class SlotService {

    public function __construct($db, $creatives) {
        $this->db = $db;
        $this->creatives = $creatives;
    }

    public function fetch($id) {
        $slot = $this->db->fetch('slots', $id, 'SELECT * FROM slots WHERE id = ?');
        $trafficking = $this->db->uncachedFetchAll('SELECT * FROM traffickings WHERE slot_id = ?', [$slot['id']]);
        $slot['offers'] = [];
        foreach ($trafficking as $t) {
            $o = $this->db->fetch('offers', $t['offer_id'], 'SELECT * FROM offers WHERE id = ?');
            $creatives = $this->db->uncachedFetchAll("SELECT * FROM offers_creatives WHERE offer_id = ?", [$o['id']]);
            foreach ($creatives as $c) {

                $o['creatives'][] = $creative = $this->creatives->fetchCreative($c['creative_id']);
            }
            $slot['offers'][] = $o;
        }
        return $slot;
    }
}

