<?php

class Html {

    public static function table($array){
        $html = '<table class="pure-table">';
        $html .= '<tr>';

        foreach($array[0] as $key => $value) {
            $html .= '<th>' . $key . '</th>';
        }
        $html .= '</tr>';
        foreach( $array as $key => $value) {
            $html .= '<tr>';
            foreach($value as $key2 => $value2) {
                $html .= '<td>' . $value2 . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    public static function linkTable($array, $key, $basePath) {
        foreach($array as $i => $row) {
            $url = $basePath . $row[$key];
            $array[$i]['link'] = "<a href=\"{$url}\">{$url}</a>";
        }
        return Html::table($array);
    }

    public static function vardump($x) {
        return "<pre>" . print_r($x, true) . "</pre>";
    }
}
