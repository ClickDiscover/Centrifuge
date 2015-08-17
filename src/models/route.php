<?php

require_once dirname(dirname(__DIR__)) . '/config.php';

class RouteFunctions {
    public static $insertSql = "INSERT INTO routes (url, lander_id) VALUES (?, ?)";

    public static function insert($app, $url, $landerId) {
        $stmt = $app->db()->prepare(RouteFunctions::$insertSql);
        return $stmt->execute(array($url, $landerId));
    }
}
