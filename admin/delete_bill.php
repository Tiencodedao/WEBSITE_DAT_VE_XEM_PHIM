<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Bills.php';

// Kiểm tra nếu là POST request và có xác nhận xóa hóa đơn
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_bill'])) {
    $bill_id = $_POST['bill_id'];

    // Gọi phương thức delete trong lớp Bill để xóa hóa đơn
    $result = Bill::delete($bill_id);

    if ($result) {
        // Sau khi xóa thành công, chuyển hướng về trang quản lý hóa đơn
        header('Location: bill.php');  // Trang hiển thị danh sách hóa đơn
        exit();
    } else {
        echo "Có lỗi khi xóa hóa đơn.";
    }
}
