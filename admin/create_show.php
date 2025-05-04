<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Shows.php';
require_once __DIR__ . '/../models/Movies.php';
require_once __DIR__ . '/../models/Rooms.php';

// Lấy danh sách phim và phòng để hiển thị dropdown
$movies = Movie::all();
$rooms = Room::all();

// Xử lý khi submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maPhim = $_POST['ma_phim'];
    $maPhong = $_POST['ma_phong'];
    $ngayChieu = $_POST['ngay_chieu'];
    $gioBatDau = $_POST['gio_bat_dau'];
    $giaVe = $_POST['gia_ve'];

    // Thêm suất chiếu mới
    try {
        $result = Show::create($maPhim, $maPhong, $ngayChieu, $gioBatDau, $giaVe);
        if ($result) {
            header("Location: show.php");
            exit();
        }
    } catch (Exception $e) {
        $error_message = "Có lỗi khi thêm suất chiếu: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <title>Thêm Suất Chiếu</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>
    <!-- NAVBAR -->
    <main class="main-content">
        <h1 class="title">Quản lý Suất Chiếu</h1>
        <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li class="divider">/</li>
            <li><a href="show.php">Suất Chiếu</a></li>
            <li class="divider">/</li>
            <li><a href="#" class="active">Thêm Mới</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Thêm Suất Chiếu Mới</h2>
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="create_show.php" method="POST">
                <div class="form-group">
                    <label for="ma_phim">Phim</label>
                    <select name="ma_phim" id="ma_phim" required>
                        <option value="">-- Chọn phim --</option>
                        <?php foreach ($movies as $movie): ?>
                            <option value="<?php echo $movie['MaPhim']; ?>">
                                <?php echo $movie['TenPhim']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ma_phong">Phòng</label>
                    <select name="ma_phong" id="ma_phong" required>
                        <option value="">-- Chọn phòng --</option>
                        <?php foreach ($rooms as $room): ?>
                            <option value="<?php echo $room['MaPhong']; ?>">
                                <?php echo $room['TenPhong']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ngay_chieu">Ngày Chiếu</label>
                    <input type="date" id="ngay_chieu" name="ngay_chieu" required>
                </div>

                <div class="form-group">
                    <label for="gio_bat_dau">Giờ Bắt Đầu</label>
                    <input type="time" id="gio_bat_dau" name="gio_bat_dau" required>
                </div>

                <div class="form-group">
                    <label for="gia_ve">Giá Vé (VNĐ)</label>
                    <input type="number" id="gia_ve" name="gia_ve" required min="0" value="75000">
                    <small>Nhập giá vé không có dấu phân cách (VD: 75000)</small>
                </div>

                <div class="form-actions">
                    <a href="show.php" class="btn-cancel">Hủy</a>
                    <button type="submit" class="btn-submit">Thêm Suất Chiếu</button>
                </div>
            </form>
        </section>
    </main>
    <!-- FOOTER -->
    <?php include('./includes/footer.php') ?>

    <style>
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group input[type="date"],
        .form-group input[type="time"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1775F1;
            box-shadow: 0 0 0 2px rgba(23, 117, 241, 0.1);
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-cancel {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e0e0e0;
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-cancel:hover {
            background-color: #d0d0d0;
        }

        .btn-submit {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1775F1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #0C5FCD;
        }

        .error-message {
            background-color: #ffebee;
            color: #d32f2f;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #d32f2f;
        }
    </style>
</body>

</html>