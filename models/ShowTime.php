<?php
require_once __DIR__ . '/../config/config.php';

class ShowTime
{
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM show_times");
        return $stmt->fetchAll();
    }

    public static function getByMovie($movieId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM show_times WHERE movie_id = ?");
        $stmt->execute([$movieId]);
        return $stmt->fetchAll();
    }
}
