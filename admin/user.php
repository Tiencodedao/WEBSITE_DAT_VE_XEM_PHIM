<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Users.php';
$users = User::all();
// print_r($movies);
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
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bxs-smile icon'></i> AdminSite</a>
        <ul class="side-menu">
            <li><a href="index.html" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li class="divide" data-text="main">_________________________________</li>
            <li>
                <a href="user.php"><i class='bx bxs-inbox icon'></i> Quản lý tài khoản <i class='bx bx-chevron-right icon-right'></i></a>
            </li>
            <li><a href="movie.php"><i class='bx bxs-chart icon'></i> Quản Lý phim</a></li>
            <li><a href="Room.html"><i class='bx bxs-widget icon'></i> Quản Lý Phòng</a></li>
            <li class="divide" data-text="table and forms">_________________________________</li>
            <li><a href="bill.html"><i class='bx bx-table icon'></i> Quản lý hóa đơn </a></li>
            <li>
                <a href="Movie_Screening.html"><i class='bx bxs-notepad icon'></i> Quản lý xuất chiếu </a>
            </li>
        </ul>
    </section>
    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
            <form action="#">
                <div class="form-group">
                    <input type="text" placeholder="Search...">
                    <i class='bx bx-search icon'></i>
                </div>
            </form>
            <a href="#" class="nav-link">
                <i class='bx bxs-bell icon'></i>
                <span class="badge">5</span>
            </a>
            <a href="#" class="nav-link">
                <i class='bx bxs-message-square-dots icon'></i>
                <span class="badge">8</span>
            </a>
            <span class="divider"></span>
            <div class="profile">
                <img src="../img/a1.jpg" alt="">
                <ul class="profile-link">
                    <li><a href="#"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                    <li><a href="#"><i class='bx bxs-cog'></i> Settings</a></li>
                    <li><a href="#"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                </ul>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main class="main-content">
            <section class="table-widget">
                <h2 class="widget-title">Danh sách phim</h2>
                <div class="widget-header">
                    <a href="create.php" class="btn-add-movie">Thêm mới</a>
                </div>
                <div class="product-table-header">
                    <span class="column-header genre-header">ID</span>
                    <span class="column-header product-header">Tên</span>
                    <span class="column-header product-header">Phân quyền</span>
                    <span class="column-header genre-header">Mật khẩu</span>
                    <span class="column-header release-date-header">Email</span>
                    <span class="column-header action-header">&nbsp;</span>
                </div>

                <ul class="product-table-list">
                    <?php foreach ($users as $user): ?>
                        <li class="product-row">
                            <div class="column product-id">
                                <?php echo $user['user_id']; ?>
                            </div>
                            <div class="column product-info">

                                <?php echo $user['user_name'] ?>
                            </div>
                            <div class="column product-info">
                                <?php echo $user['user_role']; ?>
                            </div>
                            <div class="column genre-info">
                                <?php echo $user['user_password']; ?>
                            </div>
                            <div class="column release-date-info">
                                <?php echo $user['user_email']; ?>
                            </div>
                            <div class="column action-info">
                                <!-- <button class="btn-action btn-edit" aria-label="Sửa"><i class='bx bx-edit'></i></button> -->
                                <!-- <button class="btn-action btn-delete" aria-label="Xóa"><i class='bx bx-trash'></i></button> -->
                                <div class="column action-info">
                                    <form method="POST" action="edit_user.php"> <!-- Sửa thành edit_user.php -->
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>" /> <!-- Sửa MaPhim thành id -->
                                        <button class="btn-action btn-edit" type="submit" name="edit_user" aria-label="Sửa"><i class='bx bx-edit'></i></button> <!-- Sửa edit_movie thành edit_user -->
                                    </form>
                                </div>
                                <div class="column action-info">
                                    <form method="POST" action="delete_user.php"> <!-- Thay đổi action thành delete_user.php -->
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>" /> <!-- Thay đổi MaPhim thành id -->
                                        <button class="btn-action btn-edit" type="submit" name="delete_user" aria-label="Xóa"><i class='bx bx-trash'></i></button> <!-- Thay đổi delete_movie thành delete_user -->
                                    </form>
                                </div>

                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </main>
    </section>
    <!-- NAVBAR -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="/js/script.js"></script>
</body>

</html>