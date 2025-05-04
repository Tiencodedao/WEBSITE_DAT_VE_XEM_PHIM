<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Shows.php';
require_once __DIR__ . '/../models/Movies.php';
require_once __DIR__ . '/../models/Rooms.php';

$error_message = '';
$showId = $_GET['id'] ?? $_POST['show_id'] ?? null;
$show = null;

// Lấy danh sách phim và phòng để hiển thị dropdown
$movies = Movie::all();
$rooms = Room::all();

if ($showId) {
    // Lấy thông tin chi tiết của suất chiếu
    try {
        $show = Show::find($showId);
        if (!$show) {
            $error_message = "Không tìm thấy suất chiếu với ID: $showId";
        }
    } catch (Exception $e) {
        $error_message = "Lỗi khi tìm suất chiếu: " . $e->getMessage();
    }
} else {
    $error_message = "Thiếu ID suất chiếu.";
}

// Xử lý khi submit form chỉnh sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $show) {
    $maPhim = $_POST['ma_phim'];
    $maPhong = $_POST['ma_phong'];
    $ngayChieu = $_POST['ngay_chieu'];
    $gioBatDau = $_POST['gio_bat_dau'];
    $giaVe = $_POST['gia_ve'];

    // Cập nhật suất chiếu
    try {
        $result = Show::update($showId, $maPhim, $maPhong, $ngayChieu, $gioBatDau, $giaVe);
        if ($result) {
            header("Location: show.php");
            exit();
        }
    } catch (Exception $e) {
        $error_message = "Có lỗi khi cập nhật suất chiếu: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <title>Chỉnh Sửa Suất Chiếu</title>
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
            <li><a href="#" class="active">Chỉnh Sửa</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Chỉnh Sửa Suất Chiếu</h2>
            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>

            <?php if ($show): ?>
                <form action="edit_show.php?id=<?= $showId ?>" method="POST">
                    <input type="hidden" name="show_id" value="<?= $showId ?>">

                    <div class="form-group">
                        <label for="ma_phim">Phim</label>
                        <select name="ma_phim" id="ma_phim" required>
                            <option value="">-- Chọn phim --</option>
                            <?php foreach ($movies as $movie): ?>
                                <?php
                                // Lấy MaPhim từ DB (không có trong kết quả join)
                                $selected = false;
                                if (isset($show['MaPhim']) && $show['MaPhim'] == $movie['MaPhim']) {
                                    $selected = true;
                                }
                                // Kiểm tra theo tên phim nếu không có MaPhim
                                elseif (!isset($show['MaPhim']) && isset($show['TenPhim']) && $show['TenPhim'] == $movie['TenPhim']) {
                                    $selected = true;
                                }
                                ?>
                                <option value="<?= $movie['MaPhim'] ?>" <?= $selected ? 'selected' : '' ?>>
                                    <?= $movie['TenPhim'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ma_phong">Phòng</label>
                        <select name="ma_phong" id="ma_phong" required>
                            <option value="">-- Chọn phòng --</option>
                            <?php foreach ($rooms as $room): ?>
                                <?php
                                // Lấy MaPhong từ DB (không có trong kết quả join)
                                $selected = false;
                                if (isset($show['MaPhong']) && $show['MaPhong'] == $room['MaPhong']) {
                                    $selected = true;
                                }
                                // Kiểm tra theo tên phòng nếu không có MaPhong
                                elseif (!isset($show['MaPhong']) && isset($show['TenPhong']) && $show['TenPhong'] == $room['TenPhong']) {
                                    $selected = true;
                                }
                                ?>
                                <option value="<?= $room['MaPhong'] ?>" <?= $selected ? 'selected' : '' ?>>
                                    <?= $room['TenPhong'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ngay_chieu">Ngày Chiếu</label>
                        <?php
                        // Định dạng ngày cho input date
                        $ngayChieu = isset($show['NgayChieu']) ? date('Y-m-d', strtotime($show['NgayChieu'])) : '';
                        ?>
                        <input type="date" id="ngay_chieu" name="ngay_chieu" value="<?= $ngayChieu ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gio_bat_dau">Giờ Bắt Đầu</label>
                        <?php
                        // Định dạng giờ cho input time
                        $gioBatDau = isset($show['GioBatDau']) ? $show['GioBatDau'] : '';
                        // Nếu không có trường GioBatDau riêng, thử lấy từ NgayChieu
                        if (empty($gioBatDau) && isset($show['NgayChieu'])) {
                            $gioBatDau = date('H:i', strtotime($show['NgayChieu']));
                        }
                        ?>
                        <input type="time" id="gio_bat_dau" name="gio_bat_dau" value="<?= $gioBatDau ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gia_ve">Giá Vé (VNĐ)</label>
                        <input type="number" id="gia_ve" name="gia_ve" value="<?= $show['GiaVe'] ?? 75000 ?>" required min="0">
                        <small>Nhập giá vé không có dấu phân cách (VD: 75000)</small>
                    </div>

                    <div class="form-actions">
                        <a href="show.php" class="btn-cancel">Hủy</a>
                        <button type="submit" class="btn-submit">Cập Nhật</button>
                    </div>
                </form>
            <?php endif; ?>
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