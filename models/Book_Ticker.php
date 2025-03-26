<?php
require_once __DIR__ . '/../config/config.php';

class Booking_Ticket
{
    public static function create($showTimeId, $totalPrice, $status = 0)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO booking_tickets (show_time_id, total_price, status, created_at, updated_at)
        VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$showTimeId, $totalPrice, $status]);
        return $pdo->lastInsertId();
    }
}
