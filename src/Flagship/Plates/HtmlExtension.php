<?php

namespace Flagship\Plates;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

use Flagship\Util\ArrayConvertible;

class HtmlExtension implements ExtensionInterface {

    public function register(Engine $engine) {
        $engine->registerFunction('table', [$this, 'table']);
        $engine->registerFunction('objTable', [$this, 'objTable']);
        $engine->registerFunction('linkTable', [$this, 'linkTable']);
        $engine->registerFunction('multiLinkTable', [$this, 'multiLinkTable']);
        $engine->registerFunction('vardump', [$this, 'vardump']);
    }

    public static function table($array){
        $html = '<table class="pure-table">';
        $html .= '<tr>';

        if(count($array) == 0) {
            return false;
        }

        if ($array[0] instanceof ArrayConvertible) {
            $array = array_map(function ($x) {
                return $x->toArray();
            }, $array);
        }

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

    public static function objTable($obj) {
        $arrs = array_map(function ($x) {
            return $x->toArray();
        }, $obj);
        return self::table($arrs);
    }

    public static function linkTableCol($array, $key, $basePath, $name = 'link') {
        foreach($array as $i => $row) {
            $url = $basePath . $row[$key];
            $array[$i][$name] = "<a href=\"{$url}\">{$url}</a>";
        }
        return $array;
    }

    public static function linkTable($array, $key, $basePath) {
        $array =  HtmlExtension::linkTableCol($array, $key, $basePath);
        return HtmlExtension::table($array);
    }

    public static function multiLinkTable($array, $links) {
        foreach($links as $name => $pair) {
            $array =  HtmlExtension::linkTableCol($array, $pair[0], $pair[1], $name);
        }
        return HtmlExtension::table($array);
    }

    public static function vardump($x) {
        return "<pre>" . print_r($x, true) . "</pre>";
    }
}
