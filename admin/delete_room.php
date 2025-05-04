<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Rooms.php'; // Sử dụng model Room để xử lý

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_room'])) { // Chỉnh lại thành delete_room
    $room_id = $_POST['room_id'];  // Lấy ID phòng cần xóa

    // Gọi phương thức delete trong lớp Room để xóa phòng
    $result = Room::delete($room_id); // Sử dụng phương thức delete trong model Room

    if ($result) {
        // Sau khi xóa thành công, chuyển hướng về trang quản lý phòng
        header('Location: room.php');  // Hoặc trang quản lý phòng của bạn
        exit();
    } else {
        echo "Có lỗi khi xóa phòng.";
    }
}
