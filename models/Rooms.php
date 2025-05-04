<?php
require_once __DIR__ . '/../config/config.php';

class Room
{
    // Lấy tất cả các phòng
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Phong");
        return $stmt->fetchAll();
    }

    // Tìm phòng theo ID
    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Phong WHERE MaPhong = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tạo mới phòng chiếu
    public static function create($TenPhong)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO Phong (TenPhong) 
            VALUES (?)
        ");
        return $stmt->execute([$TenPhong]);
    }

    // Cập nhật thông tin phòng chiếu
    public static function update($MaPhong, $TenPhong)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE Phong SET 
            TenPhong = ? 
            WHERE MaPhong = ?
        ");
        return $stmt->execute([$TenPhong, $MaPhong]);
    }

    // Xóa phòng chiếu theo ID
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Phong WHERE MaPhong = ?");
        return $stmt->execute([$id]);
    }
    // Lấy các phòng có suất chiếu cho phim theo ngày
    public static function getByMovieAndDate($movieId, $date)
    {
        global $pdo;
        $stmt = $pdo->prepare("
        SELECT DISTINCT p.MaPhong, p.TenPhong
        FROM Phong p
        JOIN SuatChieu s ON p.MaPhong = s.MaPhong
        WHERE s.MaPhim = ? AND s.NgayChieu = ?
    ");
        $stmt->execute([$movieId, $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
