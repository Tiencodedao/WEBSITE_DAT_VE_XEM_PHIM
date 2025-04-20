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
    public static function create($name, $email, $username, $password, $role)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO Users (name, email, user_name, password, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $username, password_hash($password, PASSWORD_DEFAULT), $role]);
    }

    // Cập nhật thông tin tài khoản
    public static function update($id, $name, $email, $username, $role)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE Users SET name = ?, email = ?, user_name = ?, role = ? WHERE user_id = ?");
        return $stmt->execute([$name, $email, $username, $role, $id]);
    }

    // Xoá tài khoản
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        return $stmt->execute([$id]);
    }

    // Đăng nhập (kiểm tra tài khoản)
    public static function authenticate($username, $password)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_name = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['user_password'])) {
            return $user;
        }

        return false;
    }
}
