<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Users.php';

$error_message = '';
$userId = $_GET['id'] ?? $_POST['user_id'] ?? null;
$user = null;

// Lấy thông tin người dùng theo ID
if ($userId) {
    $user = User::find($userId);
    if (!$user) {
        $error_message = "Không tìm thấy người dùng với ID: $userId";
    }
} else {
    $error_message = "Thiếu ID người dùng.";
}

// Xử lý khi người dùng submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $user_name = $_POST['user_name'] ?? '';
    $user_email = $_POST['user_email'] ?? '';
    $user_password = $_POST['user_password'] ?? '';

    $user_role = $_POST['user_role'] ?? 'Customer';
    $is_staff = isset($_POST['is_staff']) ? 1 : 0;

    $result = User::update($userId, $user_name, $user_email, $user_role, $is_staff);

    if ($result) {
        header("Location: user.php");
        exit();
    } else {
        $error_message = "Cập nhật thất bại. Vui lòng thử lại.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa</title>
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
            <h2 class="widget-title">Chỉnh Sửa Người Dùng</h2>

            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>

            <?php if ($user): ?>
                <form action="edit_user.php?id=<?= $userId ?>" method="POST">
                    <input type="hidden" name="user_id" value="<?= $userId ?>">

                    <div class="form-group">
                        <label for="user_name">Họ và Tên</label>
                        <input type="text" name="user_name" id="user_name" value="<?= htmlspecialchars($user['user_name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input type="email" name="user_email" id="user_email" value="<?= htmlspecialchars($user['user_email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="user_password">Mật khẩu</label>
                        <div style="position: relative;">
                            <input type="password" name="user_password" id="user_password" value="" required>
                            <span onclick="togglePassword()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                <i class='bx bx-show' id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>
                    <!-- Chỉnh sửa lại phần này để không cần nhập lại mật khẩu -->
                    <div class="form-group">
                        <label for="user_role">Vai trò</label>
                        <select name="user_role" id="user_role" required>
                            <option value="Admin" <?= $user['user_role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="Customer" <?= $user['user_role'] === 'Customer' ? 'selected' : '' ?>>Customer</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-submit">Cập Nhật</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
    </section>
    <script src="./js/edit_user.js"></script>
    <?php include('./includes/footer.php'); ?>