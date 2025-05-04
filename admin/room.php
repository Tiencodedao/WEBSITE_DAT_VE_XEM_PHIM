<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Rooms.php';

// Lấy tất cả phòng chiếu từ model Rooms
$rooms = Room::all();

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total = count($rooms);
$totalPages = ceil($total / $limit);
$offset = ($page - 1) * $limit;

// Phân trang thủ công
$rooms = array_slice($rooms, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <title>Quản lý Phòng Chiếu</title>
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>

    <!-- MAIN -->
    <main class="main-content">
        <h1 class="title">Quản lý Phòng Chiếu</h1>
        <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li class="divider">/</li>
            <li><a href="#" class="active">Phòng Chiếu</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Danh sách phòng chiếu</h2>
            <div class="widget-header">
                <a href="create_room.php" class="btn-add-movie">Thêm mới</a>
            </div>
            <div class="product-table-header">
                <span class="column-header genre-header">ID</span>
                <span class="column-header product-header">Tên Phòng</span>
                <span class="column-header status-header">Trạng Thái</span>
                <span class="column-header action-header">&nbsp;</span>
            </div>

            <ul class="product-table-list">
                <?php foreach ($rooms as $room): ?>
                    <li class="product-row">
                        <div class="column product-id">
                            <?php echo $room['MaPhong']; ?>
                        </div>
                        <div class="column product-info">
                            <?php echo $room['TenPhong']; ?>
                        </div>
                        <div class="column status-info">
                            <?php
                            $status = isset($room['TrangThai']) ? $room['TrangThai'] : 1;
                            echo $status == 1 ? '<span class="badge success">Hoạt động</span>' : '<span class="badge danger">Bảo trì</span>';
                            ?>
                        </div>
                        <div class="column action-info">
                            <a href="edit_room.php?id=<?php echo $room['MaPhong']; ?>" class="btn-action btn-edit" title="Chỉnh sửa">
                                <i class='bx bx-edit'></i>
                            </a>
                            <form method="POST" action="delete_room.php" style="display:inline;">
                                <input type="hidden" name="room_id" value="<?php echo $room['MaPhong']; ?>" />
                                <button class="btn-action btn-delete" type="submit" name="delete_room" aria-label="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?');">
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
        /* Style cho bảng phòng chiếu */
        .product-table-header {
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            height: 50px;
            border-bottom: 1px solid rgba(132, 139, 200, 0.18);
            background: var(--light);
            margin-bottom: 0;
        }

        .product-table-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .product-row {
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            height: 70px;
            /* Fixed height for all rows */
            border-bottom: 1px solid rgba(132, 139, 200, 0.18);
        }

        .column {
            padding: 0.5rem 0;
        }

        .column.product-id {
            width: 80px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .column-header.genre-header {
            width: 80px;
        }

        .column.product-info {
            flex: 1;
            font-weight: 500;
        }

        .column-header.product-header {
            flex: 1;
        }

        .status-header {
            width: 120px;
            text-align: center;
        }

        .column.status-info {
            width: 120px;
            text-align: center;
        }

        .column-header.action-header,
        .column.action-info {
            width: 100px;
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge.success {
            background-color: rgba(23, 201, 100, 0.18);
            color: #17C964;
        }

        .badge.danger {
            background-color: rgba(242, 78, 30, 0.18);
            color: #F24E1E;
        }

        /* Buttons */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 4px;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 8px;
        }

        .btn-edit:hover {
            background-color: rgba(71, 130, 218, 0.18);
            color: #4782DA;
        }

        .btn-delete:hover {
            background-color: rgba(242, 78, 30, 0.18);
            color: #F24E1E;
        }

        /* Hover effect */
        .product-row:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Responsive */
        @media (max-width: 992px) {

            .column.status-info,
            .status-header {
                width: auto;
                min-width: 80px;
            }
        }
    </style>
</body>

</html>