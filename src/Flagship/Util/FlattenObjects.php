<?php

namespace Flagship\Util;

use Flagship\Model\Lander;

class FlattenObjects {
    static function lander(Lander $lander) {
        $row = array(
            'ID' => $lander->id,
            'Notes' => $lander->notes
        );
        $row['Type'] = $lander->offers[1]->product->source;
        $row['Product 1'] = $lander->offers[1]->getName();
        $row['Product 2'] = $lander->offers[2]->getName();
        $row['Website'] = $lander->website->name;
        $row['Geo'] = $lander->geo->name;
        $row['Variants'] = json_encode($lander->variants);
        $row['Active'] = 'True';
        return $row;
    }
}
