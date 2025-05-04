<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Rooms.php';

$error_message = '';

// Xử lý khi submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenPhong = $_POST['ten_phong'];

    // Thêm phòng mới
    $result = Room::create($tenPhong);
    if ($result) {
        header("Location: room.php"); // Chuyển hướng về trang quản lý phòng
        exit();
    } else {
        $error_message = "Có lỗi khi thêm phòng. Vui lòng thử lại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <title>Thêm Phòng Chiếu</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/room.css">
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>
    <!-- NAVBAR -->
    <main class="main-content">
        <h1 class="title">Quản lý Phòng Chiếu</h1>
        <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li class="divider">/</li>
            <li><a href="room.php">Phòng Chiếu</a></li>
            <li class="divider">/</li>
            <li><a href="#" class="active">Thêm Phòng</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Thêm Phòng Chiếu Mới</h2>

            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>

            <form action="create_room.php" method="POST">
                <div class="form-group">
                    <label for="ten_phong">Tên Phòng</label>
                    <input type="text" id="ten_phong" name="ten_phong" required>
                </div>

                <button type="submit" class="btn-submit">Thêm Phòng</button>
            </form>
        </section>
    </main>
    <!-- FOOTER -->
    <?php include('./includes/footer.php') ?>
</body>

</html>