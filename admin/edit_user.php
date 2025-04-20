<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Users.php'; // Gọi model User để làm việc với dữ liệu người dùng

// Kiểm tra nếu có ID người dùng
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $user = User::find($id); // Tìm người dùng theo ID
}
// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $username = $_POST['user_name'];
    $userpassword = $_POST['user_password'];
    $useremail = $_POST['user_email'];
    $userrole = $_POST['user_role'];

    // Gọi phương thức update để cập nhật thông tin người dùng
    $result = User::update($userId, $username, $useremail, $userpassword, $userrole);

    if ($result) {
        // Nếu cập nhật thành công, chuyển hướng về trang quản lý người dùng
        header("Location: user.php");
        exit();
    } else {
        $error_message = "Có lỗi khi cập nhật người dùng. Vui lòng thử lại!";
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
    <link rel="stylesheet" href="./css/user.css">
    <title>Cập Nhật Người Dùng</title>
</head>

<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bxs-smile icon'></i> AdminSite</a>
        <ul class="side-menu">
            <li><a href="user.php"><i class='bx bxs-dashboard icon'></i> Danh sách người dùng</a></li>
            <li><a href="create_user.php"><i class='bx bxs-plus-circle icon'></i> Thêm người dùng</a></li>
            <!-- Các liên kết khác -->
        </ul>
    </section>
    <!-- CONTENT -->
    <section id="content">
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
            <!-- Thanh điều hướng -->
        </nav>

        <main class="main-content">
            <section class="table-widget">
                <h2 class="widget-title">Cập Nhật Người Dùng</h2>

                <?php if (isset($error_message)) : ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Form cập nhật người dùng -->
                <form action="edit_user.php?id=<?php echo $userId; ?>" method="POST">
                    <div class="form-group">
                        <label for="user_name">Tên:</label>
                        <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="user_email">Email:</label>
                        <input type="email" id="user_email" name="user_email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="user_password">Mật khẩu:</label>
                        <input type="password" id="user_password" name="user_password" value="" required>
                    </div>

                    <div class="form-group">
                        <label for="user_role">Vai trò:</label>
                        <select id="user_role" name="user_role" required>
                            <option value="admin" <?php echo ($user['user_role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="staff" <?php echo ($user['user_role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
                            <option value="customer" <?php echo ($user['user_role'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit">Cập Nhật</button>
                </form>

            </section>
        </main>
    </section>
    <script src="/js/script.js"></script>
</body>

</html>