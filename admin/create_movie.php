<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';

// Lấy danh sách thể loại để hiển thị dropdown
$genres = Movie::getAllGenres();

// Xử lý khi submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenPhim = $_POST['ten_phim'];
    $maTL = $_POST['ma_tl'];
    $ngayKhoiChieu = $_POST['ngay_khoi_chieu'];
    $moTa = $_POST['mo_ta'];

    // Xử lý hình ảnh
    if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === UPLOAD_ERR_OK) {
        $hinh = $_FILES['hinh']['name'];
        $targetDir = "img/uploads/";
        $targetFile = $targetDir . basename($hinh);

        if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $targetFile)) {
            // Thêm phim mới
            $result = Movie::create($tenPhim, $maTL, $ngayKhoiChieu, $moTa, $hinh);
            if ($result) {
                header("Location: movie.php");
                exit();
            } else {
                $error_message = "Có lỗi khi thêm phim. Vui lòng thử lại!";
            }
        } else {
            $error_message = "Tải lên hình ảnh thất bại.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <title>Thêm Phim</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/movie.css">
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>
    <!-- NAVBAR -->
    <!-- NAVBAR -->
    <main class="main-content">
        <h1 class="title">Quản lý Phim</h1>
        <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li class="divider">/</li>
            <li><a href="#" class="active">Phim</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Thêm Phim Mới</h2>
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="create_movie.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="ten_phim">Tên Phim</label>
                    <input type="text" id="ten_phim" name="ten_phim" required>
                </div>

                <div class="form-group">
                    <label for="ma_tl">Thể Loại</label>
                    <select name="ma_tl" id="ma_tl" required>
                        <option value="">-- Chọn thể loại --</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?php echo $genre['MATL']; ?>">
                                <?php echo $genre['TENTL']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ngay_khoi_chieu">Ngày Khởi Chiếu</label>
                    <input type="date" id="ngay_khoi_chieu" name="ngay_khoi_chieu" required>
                </div>

                <div class="form-group">
                    <label for="mo_ta">Mô Tả</label>
                    <textarea id="mo_ta" name="mo_ta" required></textarea>
                </div>

                <div class="form-group">
                    <label for="hinh">Hình ảnh</label>
                    <input type="file" id="hinh" name="hinh" accept="image/*" required>
                </div>

                <button type="submit" class="btn-submit">Thêm Phim</button>
            </form>
        </section>
    </main>
    </section>
    <!-- NAVBAR -->
    <?php include('./includes/footer.php') ?>