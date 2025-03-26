<?php
require_once __DIR__ . '/../config/config.php';

class BookingSeat
{
    public static function add($ticketId, $seatId)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO booking_seats (booking_ticket_id, seat_id, created_at, updated_at)
                               VALUES (?, ?, NOW(), NOW())");
        return $stmt->execute([$ticketId, $seatId]);
    }

    public static function getByTicket($ticketId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM booking_seats WHERE booking_ticket_id = ?");
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll();
    }
}
