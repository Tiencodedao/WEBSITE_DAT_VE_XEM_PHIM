<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';

// Khởi tạo biến lỗi nếu có
$error_message = '';
$movieId = $_GET['id'] ?? $_POST['movie_id'] ?? null;
$movie = null;

// Lấy thông tin phim theo ID
if ($movieId) {
    $movie = Movie::find($movieId);
    if (!$movie) {
        $error_message = "Không tìm thấy phim với ID: $movieId";
    }
} else {
    $error_message = "Thiếu ID phim.";
}

// Xử lý khi người dùng submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $movie) {
    $tenPhim = $_POST['ten_phim'] ?? '';
    $theLoai = $_POST['the_loai'] ?? '';
    $ngayKhoiChieu = $_POST['ngay_khoi_chieu'] ?? '';
    $moTa = $_POST['mo_ta'] ?? '';
    $hinh = $movie['Hinh']; // Mặc định là hình cũ

    // Nếu người dùng upload hình mới
    if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['hinh']['name'];
        $targetDir = "img/uploads/";
        $targetFile = $targetDir . basename($uploadedFile);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (move_uploaded_file($_FILES['hinh']['tmp_name'], $targetFile)) {
            $hinh = $uploadedFile; // Cập nhật hình mới
        }


        // Gọi update nếu không có lỗi hình
        if (!$error_message) {
            $result = Movie::update($movieId, $tenPhim, $theLoai, $ngayKhoiChieu, $moTa, $hinh);

            if ($result) {
                header("Location: movie.php");
                exit();
            } else {
                $error_message = "Cập nhật thất bại. Vui lòng thử lại.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Phim</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/movie.css">
</head>

<body>
    <section id="content">
        <nav><i class='bx bx-menu toggle-sidebar'></i></nav>
        <main class="main-content">
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
                            <label for="the_loai">Thể Loại</label>
                            <input type="text" name="the_loai" id="the_loai" value="<?= $movie['TheLoai'] ?>" required>
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
</body>

</html>