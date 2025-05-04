<?php
require_once __DIR__ . '/../config/config.php';

class User
{
    // Lấy tất cả người dùng
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm người dùng theo ID
    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới tài khoản
    public static function create($user_name, $user_email, $password, $user_role, $isStaff = false)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO Users (user_name, user_email, user_password, user_role, IsStaff) 
        VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $user_name,
            $user_email,
            password_hash($password, PASSWORD_DEFAULT),
            $user_role,
            $isStaff ? 1 : 0
        ]);
    }

    // Cập nhật thông tin tài khoản
    public static function update($id, $user_name, $user_email, $user_role, $isStaff = false)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE Users 
        SET user_name = ?, user_email = ?, user_role = ?, IsStaff = ? 
        WHERE user_id = ?");

        return $stmt->execute([
            $user_name,
            $user_email,
            $user_role,
            $isStaff ? 1 : 0,
            $id
        ]);
    }


    // Xoá tài khoản
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        return $stmt->execute([$id]);
    }

    // Đăng nhập (kiểm tra tài khoản)
    public static function authenticate($email, $password)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);



        return false;
    }
    // Lấy tất cả người dùng theo phân trang
    public static function paginate($limit, $offset)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Users LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số người dùng
    public static function countAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT COUNT(*) FROM Users");
        return $stmt->fetchColumn();
    }

    // Các hàm còn lại như find, create, update, delete...
}
