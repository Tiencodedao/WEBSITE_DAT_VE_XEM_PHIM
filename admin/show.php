<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Shows.php';

// Lấy tất cả suất chiếu từ model Shows
$shows = Show::all();

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total = count($shows); // Sử dụng hàm count của PHP
$totalPages = ceil($total / $limit);
$offset = ($page - 1) * $limit;

// Phân trang thủ công
$shows = array_slice($shows, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">

    <title>Quản lý Suất Chiếu</title>
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>

    <!-- MAIN -->
    <main class="main-content">
        <h1 class="title">Quản lý Suất Chiếu</h1>
        <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li class="divider">/</li>
            <li><a href="#" class="active">Suất Chiếu</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Danh sách suất chiếu</h2>
            <div class="widget-header">
                <a href="create_show.php" class="btn-add-movie">Thêm mới</a>
            </div>
            <div class="product-table-header">
                <span class="column-header genre-header">ID</span>
                <span class="column-header product-header">Phim</span>
                <span class="column-header genre-header">Phòng</span>
                <span class="column-header date-header">Ngày Chiếu</span>
                <span class="column-header time-header">Giờ Chiếu</span>
                <span class="column-header status-header">Giá Vé</span>
                <span class="column-header action-header">&nbsp;</span>
            </div>

            <ul class="product-table-list">
                <?php foreach ($shows as $show): ?>
                    <li class="product-row">
                        <div class="column product-id">
                            <?php echo $show['MaSuatChieu']; ?>
                        </div>
                        <div class="column product-info">
                            <?php echo $show['TenPhim']; ?>
                        </div>
                        <div class="column genre-info">
                            <?php echo $show['TenPhong']; ?>
                        </div>
                        <div class="column date-info">
                            <?php echo date('d/m/Y', strtotime($show['NgayChieu'])); ?>
                        </div>
                        <div class="column time-info">
                            <?php
                            // Hiển thị giờ chiếu từ trường GioBatDau hoặc lấy từ NgayChieu nếu không có
                            if (isset($show['GioBatDau'])) {
                                echo $show['GioBatDau'];
                            } else if (isset($show['NgayChieu'])) {
                                echo date('H:i', strtotime($show['NgayChieu']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div>
                        <div class="column status-info">
                            <?php echo number_format($show['GiaVe'], 0, ',', '.'); ?>₫
                        </div>
                        <div class="column action-info">
                            <a href="edit_show.php?id=<?php echo $show['MaSuatChieu']; ?>" class="btn-action btn-edit" title="Chỉnh sửa">
                                <i class='bx bx-edit'></i>
                            </a>
                            <form method="POST" action="delete_show.php" style="display:inline;">
                                <input type="hidden" name="show_id" value="<?php echo $show['MaSuatChieu']; ?>" />
                                <button class="btn-action btn-edit" type="submit" name="delete_show" aria-label="Xóa">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <a href="?page=1">&laquo;</a>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <a href="?page=<?php echo $totalPages; ?>">&raquo;</a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include('./includes/footer.php'); ?>

    <style>
        /* Thêm style cho cột giờ chiếu */
        .time-header {
            width: 100px;
            text-align: center;
        }

        .column.time-info {
            width: 100px;
            text-align: center;
            font-weight: 500;
            color: #1775F1;
        }

        /* Điều chỉnh lại cột ngày chiếu */
        .date-header {
            width: 120px;
            text-align: center;
        }

        .column.date-info {
            width: 120px;
            text-align: center;
        }

        /* Làm cho hàng có định dạng rõ ràng hơn */
        .product-row {
            transition: background-color 0.2s ease;
        }

        .product-row:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Responsive cho bảng */
        @media (max-width: 992px) {

            .column.time-info,
            .time-header,
            .column.date-info,
            .date-header {
                width: auto;
                min-width: 80px;
            }
        }
    </style>
</body>

</html>