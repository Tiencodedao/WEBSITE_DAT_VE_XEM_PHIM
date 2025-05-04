<?php
require_once __DIR__ . '/../config/config.php';

class Bill
{
    // Lấy tất cả các hóa đơn
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("
        SELECT h.*, p.TenPhim, r.TenPhong, s.NgayChieu 
        FROM hoadon h
        LEFT JOIN suatchieu s ON h.MaSuatChieu = s.MaSuatChieu
        LEFT JOIN phim p ON s.MaPhim = p.MaPhim
        LEFT JOIN phong r ON s.MaPhong = r.MaPhong
        ORDER BY h.MaHoaDon DESC
        ");
        return $stmt->fetchAll();
    }

    // Tìm hóa đơn theo ID
    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("
        SELECT h.*, p.TenPhim, r.TenPhong, s.NgayChieu
        FROM hoadon h
        LEFT JOIN suatchieu s ON h.MaSuatChieu = s.MaSuatChieu
        LEFT JOIN phim p ON s.MaPhim = p.MaPhim
        LEFT JOIN phong r ON s.MaPhong = r.MaPhong
        WHERE h.MaHoaDon = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tạo mới hóa đơn
    public static function create($MaSuatChieu, $Ghe, $SoLuong, $ThanhTien, $guest_name, $guest_email, $guest_phone)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO hoadon (MaSuatChieu, Ghe, SoLuong, ThanhTien, guest_name, guest_email, guest_phone) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$MaSuatChieu, $Ghe, $SoLuong, $ThanhTien, $guest_name, $guest_email, $guest_phone]);
    }

    // Cập nhật thông tin hóa đơn
    public static function update($MaHoaDon, $MaSuatChieu, $Ghe, $SoLuong, $ThanhTien, $guest_name, $guest_email, $guest_phone)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE hoadon SET 
            MaSuatChieu = ?, Ghe = ?, SoLuong = ?, ThanhTien = ?, guest_name = ?, guest_email = ?, guest_phone = ?
            WHERE MaHoaDon = ?
        ");
        return $stmt->execute([$MaSuatChieu, $Ghe, $SoLuong, $ThanhTien, $guest_name, $guest_email, $guest_phone, $MaHoaDon]);
    }

    // Xóa hóa đơn theo ID
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM hoadon WHERE MaHoaDon = ?");
        return $stmt->execute([$id]);
    }

    // Lấy tất cả suất chiếu
    public static function getAllShowtimes()
    {
        global $pdo;
        $stmt = $pdo->query("
        SELECT s.*, p.TenPhim, r.TenPhong
        FROM suatchieu s
        LEFT JOIN phim p ON s.MaPhim = p.MaPhim
        LEFT JOIN phong r ON s.MaPhong = r.MaPhong
        ORDER BY s.NgayChieu DESC, s.GioBatDau ASC
        ");
        return $stmt->fetchAll();
    }

    // Lấy hóa đơn theo phân trang
    public static function paginate($limit, $offset)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT h.*, p.TenPhim, r.TenPhong, s.NgayChieu 
            FROM hoadon h
            LEFT JOIN suatchieu s ON h.MaSuatChieu = s.MaSuatChieu
            LEFT JOIN phim p ON s.MaPhim = p.MaPhim
            LEFT JOIN phong r ON s.MaPhong = r.MaPhong
            ORDER BY h.MaHoaDon DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Đếm tổng số hóa đơn
    public static function countAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT COUNT(*) FROM hoadon");
        return $stmt->fetchColumn();
    }

    // Lấy các hóa đơn theo khoảng ngày
    public static function getByDateRange($startDate, $endDate)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT h.*, p.TenPhim, r.TenPhong, s.NgayChieu 
            FROM hoadon h
            LEFT JOIN suatchieu s ON h.MaSuatChieu = s.MaSuatChieu
            LEFT JOIN phim p ON s.MaPhim = p.MaPhim
            LEFT JOIN phong r ON s.MaPhong = r.MaPhong
            WHERE s.NgayChieu BETWEEN :startDate AND :endDate
            ORDER BY s.NgayChieu DESC
        ");
        $stmt->bindValue(':startDate', $startDate);
        $stmt->bindValue(':endDate', $endDate);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy thống kê doanh thu theo phim
    public static function getRevenueByMovie()
    {
        global $pdo;
        $stmt = $pdo->query("
            SELECT p.MaPhim, p.TenPhim, SUM(h.ThanhTien) as TongDoanhThu, COUNT(h.MaHoaDon) as SoLuongVe
            FROM hoadon h
            LEFT JOIN suatchieu s ON h.MaSuatChieu = s.MaSuatChieu
            LEFT JOIN phim p ON s.MaPhim = p.MaPhim
            GROUP BY p.MaPhim, p.TenPhim
            ORDER BY TongDoanhThu DESC
        ");
        return $stmt->fetchAll();
    }

    // Lấy thống kê doanh thu theo ngày
    public static function getRevenueByDate($startDate, $endDate)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT DATE(s.NgayChieu) as Ngay, SUM(h.ThanhTien) as TongDoanhThu, COUNT(h.MaHoaDon) as SoLuongVe
            FROM hoadon h
            LEFT JOIN suatchieu s ON h.MaSuatChieu = s.MaSuatChieu
            WHERE s.NgayChieu BETWEEN :startDate AND :endDate
            GROUP BY DATE(s.NgayChieu)
            ORDER BY Ngay ASC
        ");
        $stmt->bindValue(':startDate', $startDate);
        $stmt->bindValue(':endDate', $endDate);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tìm kiếm hóa đơn
    public static function search($keyword)
    {
        global $pdo;
        $keyword = '%' . $keyword . '%';
        $stmt = $pdo->prepare("
            SELECT h.*, p.TenPhim, r.TenPhong, s.NgayChieu 
            FROM hoadon h
            LEFT JOIN suatchieu s ON h.MaSuatChieu = s.MaSuatChieu
            LEFT JOIN phim p ON s.MaPhim = p.MaPhim
            LEFT JOIN phong r ON s.MaPhong = r.MaPhong
            WHERE h.guest_name LIKE :keyword 
               OR h.guest_email LIKE :keyword 
               OR h.guest_phone LIKE :keyword 
               OR p.TenPhim LIKE :keyword
            ORDER BY h.MaHoaDon DESC
        ");
        $stmt->bindValue(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
