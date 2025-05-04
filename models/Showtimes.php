<?php
require_once __DIR__ . '/../config/config.php';

class Showtime
{
    // Lấy giờ chiếu cho 1 phim tại 1 phòng vào 1 ngày cụ thể
    public static function getTimes($movieId, $roomId, $date)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT GioBatDau FROM SuatChieu
            WHERE MaPhim = ? AND MaPhong = ? AND NgayChieu = ?
            ORDER BY GioBatDau
        ");
        $stmt->execute([$movieId, $roomId, $date]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Lấy các ngày chiếu của một phim
    public static function getDates($movieId)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT DISTINCT NgayChieu FROM SuatChieu
            WHERE MaPhim = ?
            ORDER BY NgayChieu
        ");
        $stmt->execute([$movieId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // ✅ Tìm suất chiếu theo phim, phòng, ngày, giờ
    public static function find($movieId, $roomId, $date, $time)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * FROM SuatChieu
            WHERE MaPhim = ? AND MaPhong = ? AND NgayChieu = ? AND GioBatDau = ?
        ");
        $stmt->execute([$movieId, $roomId, $date, $time]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
