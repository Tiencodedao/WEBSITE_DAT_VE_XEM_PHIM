<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_movie'])) {
    $movie_id = $_POST['movie_id'];

    // Gọi phương thức delete trong lớp Movie để xóa phim
    $result = Movie::delete($movie_id);

    if ($result) {
        // Sau khi xóa thành công, chuyển hướng về trang quản lý phim
        header('Location: movie.php');  // Hoặc trang hiển thị phim của bạn
        exit();
    } else {
        echo "Có lỗi khi xóa phim.";
    }
}
?>
?>