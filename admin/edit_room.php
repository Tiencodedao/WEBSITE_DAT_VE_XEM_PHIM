<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Rooms.php';

$error_message = '';
$roomId = $_GET['id'] ?? $_POST['room_id'] ?? null;
$room = null;

if ($roomId) {
    $room = Room::find($roomId);
    if (!$room) {
        $error_message = "Không tìm thấy phòng chiếu với ID: $roomId";
    }
} else {
    $error_message = "Thiếu ID phòng chiếu.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $room) {
    $tenPhong = $_POST['ten_phong'] ?? '';

    // Cập nhật phòng chiếu
    if (!$error_message) {
        $result = Room::update($roomId, $tenPhong);
        if ($result) {
            header("Location: room.php");
            exit();
        } else {
            $error_message = "Cập nhật thất bại. Vui lòng thử lại.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <title>Chỉnh Sửa Phòng Chiếu</title>
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
            <li><a href="#" class="active">Phòng Chiếu</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Chỉnh Sửa Phòng Chiếu</h2>

            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>

            <?php if ($room): ?>
                <form action="edit_room.php?id=<?= $roomId ?>" method="POST">
                    <input type="hidden" name="room_id" value="<?= $roomId ?>">

                    <div class="form-group">
                        <label for="ten_phong">Tên Phòng</label>
                        <input type="text" name="ten_phong" id="ten_phong" value="<?= $room['TenPhong'] ?>" required>
                    </div>

                    <button type="submit" class="btn-submit">Cập Nhật</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
    <!-- FOOTER -->
    <?php include('./includes/footer.php') ?>
</body>

</html>