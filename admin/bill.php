<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Bills.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalBills = Bill::countAll();
$totalPages = ceil($totalBills / $limit);

$bills = Bill::paginate($limit, $offset);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">

    <title>AdminSite</title>
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>

    <!-- MAIN -->
    <main class="main-content">
        <h1 class="title">Quản lý Hóa Đơn</h1>
        <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li class="divider">/</li>
            <li><a href="#" class="active">Hóa Đơn</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Danh sách hóa đơn</h2>
            <div class="widget-header">
                <a href="create_bill.php" class="btn-add-movie">Thêm mới</a>
            </div>
            <div class="product-table-header">
                <span class="column-header genre-header">ID</span>
                <span class="column-header product-header">Khách Hàng</span>
                <span class="column-header product-header">Phim</span>
                <span class="column-header genre-header">Phòng</span>
                <span class="column-header release-date-header">Ngày Chiếu</span>
                <span class="column-header status-header">Số Lượng</span>
                <span class="column-header status-header">Tổng Tiền</span>
                <span class="column-header action-header">&nbsp;</span>
            </div>

            <ul class="product-table-list">
                <?php foreach ($bills as $bill): ?>
                    <li class="product-row">
                        <div class="column product-id">
                            <?php echo $bill['MaHoaDon']; ?>
                        </div>
                        <div class="column product-info">
                            <?php echo $bill['guest_name'] ?: 'Khách lẻ'; ?>
                        </div>
                        <div class="column product-info">
                            <?php echo $bill['TenPhim']; ?>
                        </div>
                        <div class="column genre-info">
                            <?php echo $bill['TenPhong']; ?>
                        </div>
                        <div class="column release-date-info">
                            <?php echo $bill['NgayChieu']; ?>
                        </div>
                        <div class="column status-info">
                            <?php echo $bill['SoLuong']; ?>
                        </div>
                        <div class="column status-info">
                            <?php echo number_format($bill['ThanhTien'], 0, ',', '.'); ?>₫
                        </div>
                        <div class="column action-info">
                            <a href="edit_bill.php?id=<?php echo $bill['MaHoaDon']; ?>" class="btn-action btn-edit" title="Chỉnh sửa">
                                <i class='bx bx-edit'></i>
                            </a>
                            <form method="POST" action="delete_bill.php" style="display:inline;">
                                <input type="hidden" name="bill_id" value="<?php echo $bill['MaHoaDon']; ?>" />
                                <button class="btn-action btn-edit" type="submit" name="delete_bill" aria-label="Xóa">
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