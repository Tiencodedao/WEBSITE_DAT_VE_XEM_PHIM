<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';
require_once __DIR__ . '/../models/Showtimes.php';
require_once __DIR__ . '/../models/Rooms.php';

// Chuyển hướng nếu không phải POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Lấy và xác thực dữ liệu từ form
$formData = [
    'movie_id' => $_POST['movie_id'] ?? '',
    'showtime_id' => $_POST['showtime_id'] ?? '',
    'selected_seats' => $_POST['selected_seats'] ?? '',
    'guest_name' => $_POST['guest_name'] ?? '',
    'guest_email' => $_POST['guest_email'] ?? '',
    'guest_phone' => $_POST['guest_phone'] ?? ''
];

// Kiểm tra dữ liệu bắt buộc
foreach ($formData as $key => $value) {
    if (empty($value)) {
        die("Thiếu thông tin cần thiết để xác nhận đặt vé. Vui lòng điền đầy đủ thông tin.");
    }
}

// Chuẩn bị dữ liệu
$orderId = mt_rand(10000, 999999); // Tạo ID phù hợp với INT(11)
$movieId = $formData['movie_id'];
$showtimeId = $formData['showtime_id'];
$selectedSeats = $formData['selected_seats'];
$totalPrice = (int)($_POST['total_price'] ?? 0);
$ticketPrice = (int)($_POST['ticket_price'] ?? ($totalPrice > 0 ? $totalPrice : 50000));
$guestName = $formData['guest_name'];
$guestEmail = $formData['guest_email'];
$guestPhone = $formData['guest_phone'];
$paymentMethod = $_POST['payment_method'] ?? 'momo';
$seatCount = count(explode(',', $selectedSeats));

// Lấy thông tin phim
$movie = Movie::find($movieId);
if (!$movie) {
    die("Không tìm thấy phim. Vui lòng thử lại.");
}

// Lưu vào database
$paymentSuccess = false;
try {
    // Bắt đầu transaction
    $pdo->beginTransaction();

    // Chuẩn bị và thực thi câu lệnh
    $stmt = $pdo->prepare("INSERT INTO hoadon (MaHoaDon, MaSuatChieu, Ghe, SoLuong, ThanhTien, guest_name, guest_email, guest_phone) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $paymentSuccess = $stmt->execute([
        $orderId,
        $showtimeId,
        $selectedSeats,
        $seatCount,
        $totalPrice,
        $guestName,
        $guestEmail,
        $guestPhone
    ]);

    // Nếu thành công, commit transaction
    if ($paymentSuccess) {
        $pdo->commit();
    } else {
        $pdo->rollBack();
    }
} catch (Exception $e) {
    // Ghi lại lỗi và rollback khi có ngoại lệ
    $pdo->rollBack();
    error_log("Lỗi thanh toán: " . $e->getMessage());
    $paymentSuccess = false;
}

// Lấy dữ liệu bổ sung
$selectedDate = $_POST['date'] ?? date('Y-m-d');
$selectedTime = $_POST['time'] ?? '';
$roomId = $_POST['room_id'] ?? 0;
$roomDetails = Room::find($roomId);

// Tạo QR code data
$qrCodeData = "ORDER:{$orderId}|MOVIE:{$movieId}|SEATS:{$selectedSeats}";

// Hàm format ngày tháng
function formatDate($date)
{
    return date('d/m/Y', strtotime($date));
}

// Các nhãn phương thức thanh toán
$paymentMethodLabels = [
    'momo' => 'MoMo',
    'vnpay' => 'VNPay',
    'card' => 'Thẻ tín dụng/ghi nợ'
];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $paymentSuccess ? 'Đặt vé thành công' : 'Lỗi thanh toán'; ?> - Movies</title>
    <link rel="icon" href="./img/logo_web.jpg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <!-- Navbar -->
    <header>
        <a href="./index.php" class="logo">
            <i class="bx bx-movie"></i> Movies
        </a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="./index.php">Trang chủ</a></li>
            <li><a href="./movie.php">Phim</a></li>
        </ul>
        <a href="./login.html" class="btn">Đăng nhập</a>
    </header>

    <!-- Main Content -->
    <main class="payment-result">
        <?php if ($paymentSuccess): ?>
            <!-- Thành công -->
            <div class="result-container success-container">
                <div class="result-header">
                    <div class="result-icon success">
                        <i class='bx bx-check'></i>
                    </div>
                    <h1>Thanh toán thành công</h1>
                    <p>Cảm ơn bạn đã đặt vé. Vui lòng kiểm tra email để xem thông tin chi tiết.</p>
                </div>

                <div class="ticket">
                    <div class="ticket-top">
                        <div class="movie-info">
                            <div class="movie-poster">
                                <img src="../admin/img/uploads/<?php echo htmlspecialchars($movie['Hinh'] ?? ''); ?>"
                                    alt="<?php echo htmlspecialchars($movie['TenPhim'] ?? ''); ?>">
                            </div>
                            <div class="movie-details">
                                <h2><?php echo htmlspecialchars($movie['TenPhim'] ?? ''); ?></h2>
                                <div class="movie-meta">
                                    <span><i class='bx bx-time'></i> <?php echo $movie['ThoiLuong'] ?? '90'; ?> phút</span>
                                    <span><i class='bx bx-film'></i> <?php echo htmlspecialchars($movie['TheLoai'] ?? ''); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="order-id">
                            <span>Mã đơn hàng</span>
                            <strong>#<?php echo $orderId; ?></strong>
                        </div>
                    </div>

                    <div class="ticket-content">
                        <div class="ticket-details">
                            <div class="detail-row">
                                <div class="detail-item">
                                    <i class='bx bx-calendar'></i>
                                    <div class="detail-info">
                                        <span class="label">Ngày chiếu</span>
                                        <span class="value"><?php echo formatDate($selectedDate); ?></span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <i class='bx bx-time'></i>
                                    <div class="detail-info">
                                        <span class="label">Giờ chiếu</span>
                                        <span class="value"><?php echo $selectedTime; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-item">
                                    <i class='bx bx-building'></i>
                                    <div class="detail-info">
                                        <span class="label">Phòng chiếu</span>
                                        <span class="value"><?php echo htmlspecialchars($roomDetails['TenPhong'] ?? ''); ?></span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <i class='bx bx-chair'></i>
                                    <div class="detail-info">
                                        <span class="label">Ghế</span>
                                        <span class="value"><?php echo $selectedSeats; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ticket-qr">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo urlencode($qrCodeData); ?>"
                                alt="QR Code">
                            <p>Quét mã QR để kiểm tra vé</p>
                        </div>
                    </div>

                    <div class="ticket-info">
                        <div class="info-section">
                            <h3><i class='bx bx-user'></i> Thông tin khách hàng</h3>
                            <div class="info-content">
                                <div class="info-row">
                                    <span class="info-label">Họ tên:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($guestName); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($guestEmail); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Số điện thoại:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($guestPhone); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="info-section">
                            <h3><i class='bx bx-credit-card'></i> Thông tin thanh toán</h3>
                            <div class="info-content">
                                <div class="info-row">
                                    <span class="info-label">Số lượng vé:</span>
                                    <span class="info-value"><?php echo $seatCount; ?> vé</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Giá vé:</span>
                                    <span class="info-value"><?php echo number_format($ticketPrice); ?> VNĐ</span>
                                </div>
                                <div class="info-row total">
                                    <span class="info-label">Tổng cộng:</span>
                                    <span class="info-value"><?php echo number_format($totalPrice); ?> VNĐ</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Phương thức:</span>
                                    <span class="info-value">
                                        <?php echo $paymentMethodLabels[$paymentMethod] ?? $paymentMethod; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ticket-actions">
                        <button class="btn-action" onclick="window.print()">
                            <i class='bx bx-printer'></i> In vé
                        </button>
                        <a href="index.php" class="btn-action primary">
                            <i class='bx bx-home'></i> Về trang chủ
                        </a>
                    </div>
                </div>

            </div>
        <?php else: ?>
            <!-- Thất bại -->
            <div class="result-container error-container">
                <div class="result-header">
                    <div class="result-icon error">
                        <i class='bx bx-x'></i>
                    </div>
                    <h1>Thanh toán không thành công</h1>
                    <p>Đã xảy ra lỗi trong quá trình thanh toán. Vui lòng thử lại sau hoặc liên hệ với chúng tôi để được hỗ trợ.</p>
                </div>
                <div class="error-actions">
                    <a href="javascript:history.back()" class="btn-action">
                        <i class='bx bx-arrow-back'></i> Quay lại
                    </a>
                    <a href="index.php" class="btn-action primary">
                        <i class='bx bx-home'></i> Về trang chủ
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <section class="footer">
        <a href="#" class="logo">
            <i class="bx bxs-movie"></i> Movies
        </a>
        <div class="social">
            <a href="https://www.facebook.com/"><i class='bx bxl-facebook'></i></a>
            <a href="https://x.com/"><i class='bx bxl-twitter'></i></a>
            <a href="https://www.instagram.com/"><i class='bx bxl-instagram'></i></a>
            <a href="https://www.tiktok.com/en/"><i class='bx bxl-tiktok'></i></a>
        </div>
    </section>
    <div class="copyright">
        <p>&#169; CarpoolVenom All Right Reserved.</p>
    </div>

    <style>
        :root {
            --primary-color: #e50914;
            --primary-hover: #f40612;
            --secondary-color: #141414;
            --success-color: #10b981;
            --error-color: #ef4444;
            --text-color: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.7);
            --text-light: rgba(255, 255, 255, 0.5);
            --bg-light: rgba(255, 255, 255, 0.05);
            --bg-dark: rgba(0, 0, 0, 0.4);
            --border-light: rgba(255, 255, 255, 0.1);
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Reset */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #121212;
            color: var(--text-color);
            line-height: 1.5;
        }

        /* Main Container */
        .payment-result {
            max-width: 900px;
            margin: 120px auto 50px;
            padding: 0 20px;
        }

        .result-container {
            background: var(--bg-dark);
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* Result Header */
        .result-header {
            text-align: center;
            padding: 40px 20px;
        }

        .result-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
        }

        .result-icon.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 2px solid var(--success-color);
        }

        .result-icon.error {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
            border: 2px solid var(--error-color);
        }

        .result-header h1 {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .result-header p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Ticket Styling */
        .ticket {
            background: var(--bg-light);
            border-radius: 12px;
            margin: 0 20px 30px;
            overflow: hidden;
        }

        .ticket-top {
            background: var(--secondary-color);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed var(--border-light);
        }

        .movie-info {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .movie-poster {
            width: 80px;
            flex-shrink: 0;
        }

        .movie-poster img {
            width: 100%;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .movie-details h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .movie-meta {
            display: flex;
            gap: 15px;
            color: var(--text-muted);
        }

        .movie-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .order-id {
            background: rgba(0, 0, 0, 0.3);
            padding: 10px 15px;
            border-radius: 8px;
            text-align: center;
        }

        .order-id span {
            display: block;
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 5px;
        }

        .order-id strong {
            font-size: 1.2rem;
            font-weight: 700;
        }

        /* Ticket Content */
        .ticket-content {
            padding: 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .ticket-details {
            flex: 1;
            min-width: 300px;
        }

        .detail-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-item {
            flex: 1;
            min-width: 200px;
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 10px;
        }

        .detail-item i {
            font-size: 24px;
            color: var(--primary-color);
        }

        .detail-info {
            flex: 1;
        }

        .detail-info .label {
            display: block;
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 5px;
        }

        .detail-info .value {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .ticket-qr {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            align-self: center;
        }

        .ticket-qr img {
            width: 200px;
            height: 200px;
            margin-bottom: 10px;
        }

        .ticket-qr p {
            color: #333;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Ticket Info */
        .ticket-info {
            padding: 0 25px 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .info-section {
            flex: 1;
            min-width: 300px;
        }

        .info-section h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-section h3 i {
            color: var(--primary-color);
        }

        .info-content {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--text-muted);
        }

        .info-value {
            font-weight: 600;
        }

        .info-row.total {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid var(--primary-color);
            font-size: 1.2rem;
        }

        .info-row.total .info-value {
            color: var(--primary-color);
        }

        /* Action Buttons */
        .ticket-actions,
        .error-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 25px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 25px;
            background: var(--bg-light);
            color: var(--text-color);
            border: 1px solid var(--border-light);
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            min-width: 150px;
        }

        .btn-action:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .btn-action.primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-action.primary:hover {
            background: var(--primary-hover);
            box-shadow: 0 5px 15px rgba(229, 9, 20, 0.3);
        }

        /* Error Container */
        .error-container {
            padding-bottom: 30px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Print Styles */
        @media print {

            header,
            .footer,
            .copyright,
            .ticket-actions,
            .error-actions,
            .result-header {
                display: none;
            }

            body,
            html {
                background: #fff;
                color: #000;
            }

            .payment-result {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            .result-container,
            .ticket {
                box-shadow: none;
                background: #fff;
            }

            .ticket-top {
                background: #f5f5f5;
                border-bottom: 1px dashed #ccc;
            }

            .detail-item,
            .info-content {
                background: #f5f5f5;
                color: #333;
            }

            .detail-info .label,
            .info-label {
                color: #666;
            }

            .detail-info .value,
            .info-value,
            .movie-details h2 {
                color: #000;
            }

            .detail-item i,
            .info-section h3 i {
                color: #e50914;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-result {
                padding: 0 15px;
            }

            .ticket-top {
                flex-direction: column;
                gap: 20px;
            }

            .order-id {
                width: 100%;
            }

            .movie-details h2 {
                font-size: 1.3rem;
            }

            .movie-meta {
                flex-direction: column;
                gap: 5px;
            }

            .ticket-content,
            .ticket-info {
                flex-direction: column;
            }

            .detail-row {
                flex-direction: column;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="./js/main.js"></script>
</body>

</html>