<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Users.php'; // Sử dụng model User để xử lý

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) { // Chỉnh lại thành delete_user
    $user_id = $_POST['user_id'];  // Lấy ID người dùng cần xóa

    // Gọi phương thức delete trong lớp User để xóa người dùng
    $result = User::delete($user_id); // Sử dụng phương thức delete trong model User

    if ($result) {
        // Sau khi xóa thành công, chuyển hướng về trang quản lý người dùng
        header('Location: user.php');  // Hoặc trang quản lý người dùng của bạn
        exit();
    } else {
        echo "Có lỗi khi xóa người dùng.";
    }
}
