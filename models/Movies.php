<?php
require_once __DIR__ . '/../config/config.php';

class Movie
{
    // Lấy tất cả các bộ phim
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Phim");
        return $stmt->fetchAll();
    }

    // Tìm bộ phim theo ID
    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Phim WHERE MaPhim = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tạo mới bộ phim
    public static function create($MaPhim, $TenPhim, $TheLoai, $NgayKhoiChieu, $MoTa, $Hinh)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO Phim (MaPhim, TenPhim, TheLoai, NgayKhoiChieu, MoTa, Hinh) 
            VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$MaPhim, $TenPhim, $TheLoai, $NgayKhoiChieu, $MoTa, $Hinh]);
    }

    // Cập nhật thông tin bộ phim
    public static function update($MaPhim, $TenPhim, $TheLoai, $NgayKhoiChieu, $MoTa, $Hinh)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE Phim SET 
            TenPhim = ?, TheLoai = ?, NgayKhoiChieu = ?, MoTa = ?, Hinh = ? 
            WHERE MaPhim = ?");
        return $stmt->execute([$TenPhim, $TheLoai, $NgayKhoiChieu, $MoTa, $Hinh, $MaPhim]);
    }

    // Xóa bộ phim theo ID
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Phim WHERE MaPhim = ?");
        return $stmt->execute([$id]);
    }
}
