<?php
// Cấu hình kết nối database
$host = 'localhost';           // Server host (thường là localhost)
$dbname = 'pagefim2';  // Tên database của bạn
$username = 'root';            // Tài khoản MySQL (XAMPP thường là root)
$password = '';                // Mật khẩu (XAMPP để trống)

// Khởi tạo kết nối PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Thiết lập chế độ lỗi để dễ debug
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Kết nối thành công!"; // Chỉ debug khi cần

} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
