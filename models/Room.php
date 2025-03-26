<?php
require_once __DIR__ . '/../config/config.php';

class Room
{
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM rooms");
        return $stmt->fetchAll();
    }
}
