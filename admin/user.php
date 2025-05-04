<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Users.php';

// Pagination setup
$limit = 5; // Số người dùng mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy tổng số người dùng và tổng số trang
$totalUsers = User::countAll();
$totalPages = ceil($totalUsers / $limit);

// Lấy người dùng theo phân trang
$users = User::paginate($limit, $offset);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản</title>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/movie.css">
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>
    <main class="main-content">
        <h1 class="title">Quản lý Tài khoản</h1>
        <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li class="divider">/</li>
            <li><a href="#" class="active">Tài Khoản</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Danh sách tài khoản</h2>
            <div class="widget-header">
                <a href="create_user.php" class="btn-add-user">Thêm tài khoản</a>
            </div>
            <div class="product-table-header">
                <span class="column-header genre-header">ID</span>
                <span class="column-header product-header">Họ Tên</span>
                <span class="column-header product-header">Email</span>
                <span class="column-header genre-header">Vai trò</span>
                <!-- <span class="column-header genre-header">Nhân viên</span> -->
                <span class="column-header action-header">&nbsp;</span>
            </div>

            <ul class="product-table-list">
                <?php foreach ($users as $user): ?>
                    <li class="product-row">
                        <div class="column product-id"><?= htmlspecialchars($user['user_id']) ?></div>
                        <div class="column product-info"><?= htmlspecialchars($user['user_name']) ?></div>
                        <div class="column product-info"><?= htmlspecialchars($user['user_email']) ?></div>
                        <div class="column genre-info"><?= htmlspecialchars($user['user_role']) ?></div>
                        <div class="column action-info">
                            <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="btn-action btn-edit" title="Chỉnh sửa">
                                <i class='bx bx-edit'></i>
                            </a>
                            <form method="POST" action="delete_user.php" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>" />
                                <button class="btn-action btn-edit" type="submit" name="delete_user" aria-label="Xóa"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="pagination">
                <a href="?page=1">&laquo;</a> <!-- Trang đầu -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                <a href="?page=<?php echo $totalPages; ?>">&raquo;</a> <!-- Trang cuối -->
            </div>
        </section>
    </main>
    </section>
    <?php include('./includes/footer.php'); ?>