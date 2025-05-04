<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalMovies = Movie::countAll();
$totalPages = ceil($totalMovies / $limit);

$movies = Movie::paginate($limit, $offset);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/movie.css">
    <title>AdminSite</title>
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>

    <!-- MAIN -->
    <main class="main-content">
        <h1 class="title">Quản lý Phim</h1>
        <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li class="divider">/</li>
            <li><a href="#" class="active">Phim</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Danh sách phim</h2>
            <div class="widget-header">
                <a href="create_movie.php" class="btn-add-movie">Thêm mới</a>
            </div>
            <div class="product-table-header">
                <span class="column-header genre-header">ID</span>
                <span class="column-header product-header">Hình</span>
                <span class="column-header product-header">Phim</span>
                <span class="column-header genre-header">Thể Loại</span>
                <span class="column-header release-date-header">Ngày Khởi Chiếu</span>
                <span class="column-header action-header">&nbsp;</span>
            </div>

            <ul class="product-table-list">
                <?php foreach ($movies as $movie): ?>
                    <li class="product-row">
                        <div class="column product-id">
                            <?php echo $movie['MaPhim']; ?>
                        </div>
                        <div class="column product-info">
                            <img src="../admin/img/uploads/<?php echo $movie['Hinh']; ?>" alt="<?php echo $movie['TenPhim']; ?>" class="movie-image">
                        </div>
                        <div class="column product-info">
                            <?php echo $movie['TenPhim']; ?>
                        </div>
                        <div class="column genre-info">
                            <?php echo $movie['TENTL']; ?>
                        </div>
                        <div class="column release-date-info">
                            <?php echo $movie['NgayKhoiChieu']; ?>
                        </div>
                        <div class="column action-info">
                            <a href="edit_movie.php?id=<?php echo $movie['MaPhim']; ?>" class="btn-action btn-edit" title="Chỉnh sửa">
                                <i class='bx bx-edit'></i>
                            </a>
                            <form method="POST" action="delete_movie.php" style="display:inline;">
                                <input type="hidden" name="movie_id" value="<?php echo $movie['MaPhim']; ?>" />
                                <button class="btn-action btn-edit" type="submit" name="delete_movie" aria-label="Xóa">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Pagination -->
            <div class="pagination">
                <!-- Trang đầu và trang trước -->
                <a href="?page=1">&laquo;</a> <!-- Trang đầu -->
                <!-- Hiển thị các trang -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                <!-- Trang tiếp và trang cuối -->
                <a href="?page=<?php echo $totalPages; ?>">&raquo;</a> <!-- Trang cuối -->
            </div>

        </section>
    </main>

    <?php include('./includes/footer.php'); ?>
</body>

</html>