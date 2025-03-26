<?php
require_once __DIR__ . '/../config/config.php';

class Seat
{
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM seats");
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM seats WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
