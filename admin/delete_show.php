<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Shows.php';

// Kiểm tra nếu là POST request và có xác nhận xóa suất chiếu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_show'])) {
    $show_id = $_POST['show_id'];

    try {
        // Gọi phương thức delete trong lớp Show để xóa suất chiếu
        $result = Show::delete($show_id);

        if ($result) {
            // Sau khi xóa thành công, chuyển hướng về trang quản lý suất chiếu
            header('Location: show.php');
            exit();
        } else {
            // Nếu có lỗi, hiển thị thông báo
            $error_message = "Có lỗi khi xóa suất chiếu.";
        }
    } catch (Exception $e) {
        // Bắt ngoại lệ nếu có
        $error_message = "Lỗi: " . $e->getMessage();
    }
}

// Nếu không phải POST request hoặc không có xác nhận xóa
if (!isset($_POST['delete_show'])) {
    header('Location: show.php');
    exit();
}
