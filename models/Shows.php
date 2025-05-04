<?php
require_once __DIR__ . '/../config/config.php';

class Show
{
    // Lấy tất cả suất chiếu
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("
            SELECT sc.MaSuatChieu, p.TenPhim, ph.TenPhong, sc.NgayChieu, sc.GioBatDau, sc.GiaVe
            FROM suatchieu sc
            LEFT JOIN phim p ON sc.MaPhim = p.MaPhim
            LEFT JOIN phong ph ON sc.MaPhong = ph.MaPhong
        ");

        // Kiểm tra lỗi nếu có
        if ($stmt === false) {
            throw new Exception("Error fetching all showtimes");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy tất cả dữ liệu dưới dạng mảng kết hợp
    }

    // Tìm suất chiếu theo ID
    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT sc.MaSuatChieu, sc.MaPhim, sc.MaPhong, p.TenPhim, ph.TenPhong, sc.NgayChieu, sc.GioBatDau, sc.GiaVe
            FROM suatchieu sc
            LEFT JOIN phim p ON sc.MaPhim = p.MaPhim
            LEFT JOIN phong ph ON sc.MaPhong = ph.MaPhong
            WHERE sc.MaSuatChieu = ?
        ");
        $stmt->execute([$id]);

        // Kiểm tra lỗi nếu có
        if ($stmt === false) {
            throw new Exception("Error fetching showtime by ID");
        }

        return $stmt->fetch(PDO::FETCH_ASSOC); // Lấy dữ liệu dưới dạng mảng kết hợp
    }

    // Tạo mới suất chiếu
    public static function create($MaPhim, $MaPhong, $NgayChieu, $GioBatDau, $GiaVe)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO suatchieu (MaPhim, MaPhong, NgayChieu, GioBatDau, GiaVe)
            VALUES (?, ?, ?, ?, ?)
        ");
        $result = $stmt->execute([$MaPhim, $MaPhong, $NgayChieu, $GioBatDau, $GiaVe]);

        // Kiểm tra lỗi nếu có
        if ($result === false) {
            throw new Exception("Error creating showtime");
        }

        return $result;
    }

    // Cập nhật suất chiếu
    public static function update($MaSuatChieu, $MaPhim, $MaPhong, $NgayChieu, $GioBatDau, $GiaVe)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE suatchieu 
            SET MaPhim = ?, MaPhong = ?, NgayChieu = ?, GioBatDau = ?, GiaVe = ? 
            WHERE MaSuatChieu = ?
        ");
        $result = $stmt->execute([$MaPhim, $MaPhong, $NgayChieu, $GioBatDau, $GiaVe, $MaSuatChieu]);

        // Kiểm tra lỗi nếu có
        if ($result === false) {
            throw new Exception("Error updating showtime");
        }

        return $result;
    }

    // Xóa suất chiếu
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM suatchieu WHERE MaSuatChieu = ?");
        $result = $stmt->execute([$id]);

        // Kiểm tra lỗi nếu có
        if ($result === false) {
            throw new Exception("Error deleting showtime");
        }

        return $result;
    }

    // Đếm tổng số suất chiếu
    public static function count()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT COUNT(*) FROM suatchieu");
        return $stmt->fetchColumn();
    }

    // Lấy danh sách suất chiếu có phân trang
    public static function paginate($limit, $offset)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT sc.MaSuatChieu, p.TenPhim, ph.TenPhong, sc.NgayChieu, sc.GioBatDau, sc.GiaVe
            FROM suatchieu sc
            LEFT JOIN phim p ON sc.MaPhim = p.MaPhim
            LEFT JOIN phong ph ON sc.MaPhong = ph.MaPhong
            ORDER BY sc.NgayChieu DESC, sc.GioBatDau DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
