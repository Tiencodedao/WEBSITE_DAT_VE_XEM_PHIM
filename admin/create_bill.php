<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Bills.php';
require_once __DIR__ . '/../models/Shows.php';
require_once __DIR__ . '/../models/Movies.php';

// Lấy danh sách suất chiếu để hiển thị dropdown
$shows = Show::all();

// Xử lý khi submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maSuatChieu = $_POST['ma_suat_chieu'];
    $guestName = $_POST['guest_name'];
    $guestEmail = $_POST['guest_email'];
    $guestPhone = $_POST['guest_phone'];
    $ghe = $_POST['ghe'];
    $soLuong = $_POST['so_luong'];
    $thanhTien = $_POST['thanh_tien'];

    // Thêm hóa đơn mới
    $result = Bill::create($maSuatChieu, $ghe, $soLuong, $thanhTien, $guestName, $guestEmail, $guestPhone);
    if ($result) {
        header("Location: bill.php");
        exit();
    } else {
        $error_message = "Có lỗi khi thêm hóa đơn. Vui lòng thử lại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <title>Thêm Hóa Đơn</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <!-- SIDEBAR -->
    <?php include('./includes/header.php') ?>
    <!-- NAVBAR -->
    <main class="main-content">
        <h1 class="title">Quản lý Hóa Đơn</h1>
        <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li class="divider">/</li>
            <li><a href="bill.php">Hóa Đơn</a></li>
            <li class="divider">/</li>
            <li><a href="#" class="active">Thêm Mới</a></li>
        </ul>
        <br>
        <section class="table-widget">
            <h2 class="widget-title">Thêm Hóa Đơn Mới</h2>
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="create_bill.php" method="POST">
                <div class="form-group">
                    <label for="ma_suat_chieu">Suất Chiếu</label>
                    <select name="ma_suat_chieu" id="ma_suat_chieu" required onchange="updatePrice()">
                        <option value="">-- Chọn suất chiếu --</option>
                        <?php foreach ($shows as $show): ?>
                            <option value="<?php echo $show['MaSuatChieu']; ?>" data-price="<?php echo $show['GiaVe']; ?>">
                                <?php echo $show['TenPhim']; ?> - <?php echo $show['TenPhong']; ?> - <?php echo $show['NgayChieu']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="guest_name">Tên Khách Hàng</label>
                    <input type="text" id="guest_name" name="guest_name" required>
                </div>

                <div class="form-group">
                    <label for="guest_email">Email</label>
                    <input type="email" id="guest_email" name="guest_email">
                </div>

                <div class="form-group">
                    <label for="guest_phone">Số Điện Thoại</label>
                    <input type="text" id="guest_phone" name="guest_phone">
                </div>

                <div class="form-group">
                    <label for="ghe">Ghế</label>
                    <input type="text" id="ghe" name="ghe" required>
                    <small>Nhập các số ghế, phân cách bằng dấu phẩy (VD: A01, A02, B05)</small>
                </div>

                <div class="form-group">
                    <label for="so_luong">Số Lượng</label>
                    <input type="number" id="so_luong" name="so_luong" value="1" required min="1" onchange="calculateTotal()">
                </div>

                <div class="form-group">
                    <label for="thanh_tien">Thành Tiền (VNĐ)</label>
                    <input type="number" id="thanh_tien" name="thanh_tien" required min="0">
                </div>

                <div class="form-actions">
                    <a href="bill.php" class="btn-cancel">Hủy</a>
                    <button type="submit" class="btn-submit">Thêm Hóa Đơn</button>
                </div>
            </form>
        </section>
    </main>
    <!-- FOOTER -->
    <?php include('./includes/footer.php') ?>

    <style>
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1775F1;
            box-shadow: 0 0 0 2px rgba(23, 117, 241, 0.1);
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-cancel {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e0e0e0;
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-cancel:hover {
            background-color: #d0d0d0;
        }

        .btn-submit {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1775F1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #0C5FCD;
        }

        .error-message {
            background-color: #ffebee;
            color: #d32f2f;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #d32f2f;
        }
    </style>

    <script>
        // Tự động tính thành tiền khi thay đổi số lượng hoặc chọn suất chiếu
        function updatePrice() {
            calculateTotal();
        }

        function calculateTotal() {
            const selectElement = document.getElementById('ma_suat_chieu');
            const selectedOption = selectElement.options[selectElement.selectedIndex];

            if (selectedOption.value !== '') {
                const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
                const quantity = parseInt(document.getElementById('so_luong').value) || 0;
                const total = price * quantity;

                document.getElementById('thanh_tien').value = total;
            }
        }

        // Tính toán ban đầu khi trang tải xong
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
        });
    </script>
</body>

</html>