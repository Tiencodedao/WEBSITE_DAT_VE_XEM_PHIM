<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Users.php';

// Xử lý form thêm tài khoản
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_role = $_POST['user_role'];
    $is_staff = isset($_POST['is_staff']) ? 1 : 0;

    $success = User::create($user_name, $user_email, $user_password, $user_role, $is_staff);

    if ($success) {
        header("Location: user.php");
        exit();
    } else {
        $error_message = "Đã xảy ra lỗi khi thêm người dùng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người Dùng</title>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/movie.css">
</head>

<body>
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
            <h2 class="widget-title">Thêm Người Dùng</h2>

            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="create_user.php" method="POST">
                <div class="form-group">
                    <label for="user_name">Họ và Tên</label>
                    <input type="text" id="user_name" name="user_name" required>
                </div>

                <div class="form-group">
                    <label for="user_email">Email</label>
                    <input type="email" id="user_email" name="user_email" required>
                </div>

                <div class="form-group">
                    <label for="user_password">Mật khẩu</label>
                    <input type="password" id="user_password" name="user_password" required>
                </div>

                <div class="form-group">
                    <label for="user_role">Vai trò</label>
                    <select id="user_role" name="user_role" required>
                        <option value="Admin">Admin</option>
                        <option value="Customer">Customer</option>
                    </select>
                </div>
                <!-- 
                    <div class="form-group checkbox-group">
                        <label><input type="checkbox" name="is_staff"> Là nhân viên</label>
                    </div> -->

                <button type="submit" class="btn-submit">Thêm Tài Khoản</button>
            </form>
        </section>
    </main>
    </section>

    <?php include('./includes/footer.php'); ?>