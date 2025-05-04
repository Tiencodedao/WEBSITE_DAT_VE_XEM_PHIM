<?php
require_once __DIR__ . '/../config/config.php';

class Movie
{
    // Lấy tất cả các bộ phim
    public static function all()
    {
        global $pdo;
        // $stmt = $pdo->query("SELECT * FROM Phim");
        $stmt = $pdo->query("
        SELECT p.*, t.TENTL 
        FROM phim p 
        LEFT JOIN theloai t ON p.MATL = t.MATL
        ");
        return $stmt->fetchAll();
    }

    // Tìm bộ phim theo ID
    public static function find($id)
    {
        global $pdo;
        // $stmt = $pdo->prepare("SELECT * FROM Phim WHERE MaPhim = ?");
        $stmt = $pdo->prepare("
        SELECT p.*, t.TENTL
        FROM phim p 
        LEFT JOIN theloai t ON p.MATL = t.MATL where p.MaPhim = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tạo mới bộ phim
    public static function create($TenPhim, $MaTL, $NgayKhoiChieu, $MoTa, $Hinh)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO phim (TenPhim, MaTL, NgayKhoiChieu, MoTa, Hinh) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$TenPhim, $MaTL, $NgayKhoiChieu, $MoTa, $Hinh]);
    }

    // Cập nhật thông tin bộ phim
    public static function update($MaPhim, $TenPhim, $MATL, $NgayKhoiChieu, $MoTa, $Hinh)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE phim SET 
            TenPhim = ?, MaTL = ?, NgayKhoiChieu = ?, MoTa = ?, Hinh = ? 
            WHERE MaPhim = ?
        ");
        return $stmt->execute([$TenPhim, $MATL, $NgayKhoiChieu, $MoTa, $Hinh, $MaPhim]);
    }

    // Xóa bộ phim theo ID
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM phim WHERE MaPhim = ?");
        return $stmt->execute([$id]);
    }
    // Lấy tất cả thể loại
    public static function getAllGenres()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM theloai");
        return $stmt->fetchAll();
    }
    // Lấy phim theo phân trang
    public static function paginate($limit, $offset)
    {
        global $pdo;
        $stmt = $pdo->prepare("
                SELECT p.*, t.TENTL 
                FROM phim p 
                LEFT JOIN theloai t ON p.MATL = t.MATL
                LIMIT :limit OFFSET :offset
            ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Đếm tổng số phim
    public static function countAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT COUNT(*) FROM phim");
        return $stmt->fetchColumn();
    }
    // Lấy phim theo thể loại, loại trừ phim hiện tại
    public static function getByGenre($maTL, $excludeId = null, $limit = 4)
    {
        global $pdo;

        $sql = "
                SELECT p.*, t.TENTL 
                FROM phim p 
                LEFT JOIN theloai t ON p.MATL = t.MATL
                WHERE p.MATL = :maTL
            ";

        // Nếu có ID phim cần loại trừ
        if ($excludeId) {
            $sql .= " AND p.MaPhim != :excludeId";
        }

        // Giới hạn số phim trả về và sắp xếp ngẫu nhiên
        $sql .= " ORDER BY RAND() LIMIT :limit";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':maTL', $maTL, PDO::PARAM_INT);

        if ($excludeId) {
            $stmt->bindValue(':excludeId', $excludeId, PDO::PARAM_INT);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    // Lấy phim đang chiếu: NgayKhoiChieu <= hôm nay
    public static function getNowPlaying()
    {
        global $pdo;
        $stmt = $pdo->prepare("
        SELECT p.*, t.TENTL
        FROM phim p
        LEFT JOIN theloai t ON p.MATL = t.MATL
        WHERE NgayKhoiChieu <= CURDATE()
        ORDER BY NgayKhoiChieu DESC
        LIMIT 20
    ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy phim sắp chiếu: NgayKhoiChieu > hôm nay
    public static function getComingSoon()
    {
        global $pdo;
        $stmt = $pdo->prepare("
        SELECT p.*, t.TENTL
        FROM phim p
        LEFT JOIN theloai t ON p.MATL = t.MATL
        WHERE NgayKhoiChieu > CURDATE()
        ORDER BY NgayKhoiChieu ASC
        LIMIT 20
    ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
