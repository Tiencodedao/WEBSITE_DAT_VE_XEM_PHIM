<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';

$error_message = '';
$movieId = $_GET['id'] ?? $_POST['movie_id'] ?? null;
$movie = null;
$genres = Movie::getAllGenres(); // Lấy thể loại để tạo dropdown

if ($movieId) {
    $movie = Movie::find($movieId);
    if (!$movie) {
        $error_message = "Không tìm thấy phim với ID: $movieId";
    }
} else {
    $error_message = "Thiếu ID phim.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $movie) {
    $tenPhim = $_POST['ten_phim'] ?? '';
    $maTL = $_POST['ma_tl'] ?? '';
    $ngayKhoiChieu = $_POST['ngay_khoi_chieu'] ?? '';
    $moTa = $_POST['mo_ta'] ?? '';
    $hinh = $movie['Hinh']; // giữ hình cũ nếu không đổi

    // Nếu người dùng upload hình mới
    if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['hinh']['name'];
        $targetDir = "img/uploads/";
        $targetFile = $targetDir . basename($uploadedFile);

        if (move_uploaded_file($_FILES['hinh']['tmp_name'], $targetFile)) {
            $hinh = $uploadedFile;
        }
    }

    // Cập nhật phim
    if (!$error_message) {
        $result = Movie::update($movieId, $tenPhim, $maTL, $ngayKhoiChieu, $moTa, $hinh);
        if ($result) {
            header("Location: movie.php");
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
    <title>Chỉnh Sửa Phim</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/movie.css">
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>
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
            <h2 class="widget-title">Chỉnh Sửa Phim</h2>

            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>

            <?php if ($movie): ?>
                <form action="edit_movie.php?id=<?= $movieId ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="ten_phim">Tên Phim</label>
                        <input type="text" name="ten_phim" id="ten_phim" value="<?= $movie['TenPhim'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="ma_tl">Thể Loại</label>
                        <select name="ma_tl" id="ma_tl" required>
                            <option value="">-- Chọn thể loại --</option>
                            <?php foreach ($genres as $genre): ?>
                                <option value="<?= $genre['MATL'] ?>" <?= $movie['MATL'] == $genre['MATL'] ? 'selected' : '' ?>>
                                    <?= $genre['TENTL'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ngay_khoi_chieu">Ngày Khởi Chiếu</label>
                        <input type="date" name="ngay_khoi_chieu" id="ngay_khoi_chieu" value="<?= $movie['NgayKhoiChieu'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="mo_ta">Mô Tả</label>
                        <textarea name="mo_ta" id="mo_ta" required><?= $movie['MoTa'] ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="hinh">Hình ảnh</label>
                        <input type="file" name="hinh" id="hinh" accept="image/*">
                        <div class="current-image">
                            <p>Hình ảnh hiện tại:</p>
                            <img src="img/uploads/<?= $movie['Hinh'] ?>" alt="Ảnh hiện tại" width="100">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Cập Nhật</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
    </section>
    <!-- NAVBAR -->
    <?php include('./includes/footer.php') ?>