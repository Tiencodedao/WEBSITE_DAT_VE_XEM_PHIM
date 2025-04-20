<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';

// Xử lý form khi người dùng nhấn nút "Thêm phim"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form
    $tenPhim = $_POST['ten_phim'];
    $theLoai = $_POST['the_loai'];
    $ngayKhoiChieu = $_POST['ngay_khoi_chieu'];
    $moTa = $_POST['mo_ta'];

    // Xử lý hình ảnh
    if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === UPLOAD_ERR_OK) {
        // Tải lên hình ảnh
        $hinh = $_FILES['hinh']['name'];
        $targetDir = "img/uploads/"; // Thư mục lưu trữ hình ảnh
        $targetFile = $targetDir . basename($hinh);

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $targetFile)) {
            // Gọi phương thức để thêm phim mới vào cơ sở dữ liệu
            $result = Movie::create(null, $tenPhim, $theLoai, $ngayKhoiChieu, $moTa, $hinh);
            if ($result) {
                // Nếu thêm phim thành công, chuyển hướng về danh sách phim
                header("Location: movie.php");
                exit();
            } else {
                // Nếu có lỗi xảy ra, hiển thị thông báo
                $error_message = "Có lỗi khi thêm phim. Vui lòng thử lại!";
            }
        } else {
            $error_message = "Có lỗi khi tải lên hình ảnh.";
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
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/movie.css">
    <title>Thêm Phim</title>
</head>

<body>

    <!-- CONTENT -->
    <section id="content">
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
        </nav>

        <main class="main-content">
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
                        <label for="the_loai">Thể Loại</label>
                        <input type="text" id="the_loai" name="the_loai" required>
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

    <script src="/js/script.js"></script>
</body>

</html>