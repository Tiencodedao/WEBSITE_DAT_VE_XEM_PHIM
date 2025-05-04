<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';
require_once __DIR__ . '/../models/Showtimes.php';
require_once __DIR__ . '/../models/Rooms.php';

// Redirect if not a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Validate required parameters
if (
    !isset($_POST['movie_id']) || empty($_POST['movie_id']) ||
    !isset($_POST['room_id']) || empty($_POST['room_id']) ||
    !isset($_POST['date']) || empty($_POST['date']) ||
    !isset($_POST['time']) || empty($_POST['time']) ||
    !isset($_POST['showtime_id']) || empty($_POST['showtime_id']) ||
    !isset($_POST['selected_seats']) || empty($_POST['selected_seats']) ||
    !isset($_POST['total_price']) || empty($_POST['total_price'])
) {
    die("Thiếu thông tin cần thiết để thanh toán.");
}

// Get parameters
$movieId = $_POST['movie_id'];
$roomId = $_POST['room_id'];
$selectedDate = $_POST['date'];
$selectedTime = $_POST['time'];
$showtimeId = $_POST['showtime_id'];
$selectedSeats = explode(',', $_POST['selected_seats']);
$totalPrice = (int)$_POST['total_price'];

// Get movie details
$movie = Movie::find($movieId);
if (!$movie) {
    die("Không tìm thấy phim.");
}

// Get room details
$roomDetails = Room::find($roomId);
if (!$roomDetails) {
    die("Không tìm thấy phòng chiếu.");
}

// Get showtime details
$showtimeDetails = Showtime::find($movieId, $roomId, $selectedDate, $selectedTime);
if (!$showtimeDetails) {
    die("Không tìm thấy suất chiếu.");
}

// Format date
function formatDate($date)
{
    return date('d/m/Y', strtotime($date));
}

// Get ticket price
$ticketPrice = isset($showtimeDetails['GiaVe']) ? $showtimeDetails['GiaVe'] : 50000; // default

// Generate order ID
$orderId = time() . rand(1000, 9999);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thanh toán vé xem phim <?php echo htmlspecialchars($movie['TenPhim']); ?></title>
    <link rel="icon" href="./img/logo_web.jpg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <!-- Box Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <!-- Navbar -->
    <header>
        <a href="./index.php" class="logo">
            <i class="bx bx-movie"></i> Movies
        </a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <!-- Menu -->
        <ul class="navbar">
            <li><a href="./index.php">Trang chủ</a></li>
            <li><a href="./movie.php">Phim</a></li>
        </ul>
        <a href="./login.html" class="btn">Đăng nhập</a>
    </header>

    <main class="payment-container">
        <!-- Payment Page -->
        <div class="payment-wrapper">
            <div class="payment-summary">
                <h2>Thông tin thanh toán</h2>

                <div class="order-summary">
                    <div class="movie-details">
                        <div class="movie-poster">
                            <img src="../admin/img/uploads/<?php echo htmlspecialchars($movie['Hinh']); ?>" alt="<?php echo htmlspecialchars($movie['TenPhim']); ?>">
                        </div>
                        <div class="movie-info">
                            <h3><?php echo htmlspecialchars($movie['TenPhim']); ?></h3>
                            <div class="info-item">
                                <span class="label">Phòng chiếu:</span>
                                <span class="value"><?php echo htmlspecialchars($roomDetails['TenPhong']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Ngày chiếu:</span>
                                <span class="value"><?php echo formatDate($selectedDate); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Giờ chiếu:</span>
                                <span class="value"><?php echo $selectedTime; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Ghế đã chọn:</span>
                                <span class="value"><?php echo implode(', ', $selectedSeats); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Số lượng vé:</span>
                                <span class="value"><?php echo count($selectedSeats); ?> vé</span>
                            </div>
                            <div class="info-item total">
                                <span class="label">Tổng thanh toán:</span>
                                <span class="value"><?php echo number_format($totalPrice); ?> VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="payment-form">
                <h2>Thông tin khách hàng</h2>

                <form id="customerForm" method="post" action="confirm.php">
                    <!-- Hidden fields -->
                    <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
                    <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">
                    <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
                    <input type="hidden" name="date" value="<?php echo $selectedDate; ?>">
                    <input type="hidden" name="time" value="<?php echo $selectedTime; ?>">
                    <input type="hidden" name="showtime_id" value="<?php echo $showtimeId; ?>">
                    <input type="hidden" name="selected_seats" value="<?php echo $_POST['selected_seats']; ?>">
                    <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">
                    <input type="hidden" name="ticket_price" value="<?php echo $ticketPrice; ?>">

                    <!-- Customer info fields -->
                    <div class="form-group">
                        <label for="name">Họ và tên <span class="required">*</span></label>
                        <input type="text" id="name" name="guest_name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="guest_email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại <span class="required">*</span></label>
                        <input type="tel" id="phone" name="guest_phone" pattern="[0-9]{10}" title="Số điện thoại phải có 10 số" required>
                    </div>

                    <h3>Phương thức thanh toán</h3>

                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="payment-momo" name="payment_method" value="momo" checked>
                            <label for="payment-momo">
                                <img src="./img/momo-logo.png" alt="MoMo">
                                <span>MoMo</span>
                            </label>
                        </div>

                        <div class="payment-method">
                            <input type="radio" id="payment-vnpay" name="payment_method" value="vnpay">
                            <label for="payment-vnpay">
                                <img src="./img/vnpay-logo.png" alt="VNPay">
                                <span>VNPay</span>
                            </label>
                        </div>

                        <div class="payment-method">
                            <input type="radio" id="payment-card" name="payment_method" value="card">
                            <label for="payment-card">
                                <img src="./img/card-icon.png" alt="Credit/Debit Card">
                                <span>Thẻ ngân hàng</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="javascript:history.back()" class="btn-back">Quay lại</a>
                        <button type="submit" class="btn-submit">Xác nhận thanh toán</button>
                    </div>
                </form>
            </div>
        </div>
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
        /* Main container */
        .payment-container {
            margin: 120px auto 50px;
            max-width: 1200px;
            padding: 0 20px;
        }

        /* Payment wrapper */
        .payment-wrapper {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        /* Payment summary section */
        .payment-summary {
            flex: 1;
            min-width: 300px;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .payment-summary h2,
        .payment-form h2 {
            font-size: 1.6rem;
            margin-bottom: 25px;
            color: #fff;
            position: relative;
            padding-bottom: 12px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .payment-summary h2::after,
        .payment-form h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--main-color);
        }

        .order-summary {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 20px;
        }

        .movie-details {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .movie-poster {
            width: 140px;
            flex-shrink: 0;
        }

        .movie-poster img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .movie-info {
            flex: 1;
            min-width: 200px;
        }

        .movie-info h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item .label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }

        .info-item .value {
            font-weight: 700;
            color: #fff;
            text-align: right;
        }

        .info-item.total {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid rgba(255, 44, 31, 0.5);
            font-size: 1.2rem;
        }

        .info-item.total .value {
            color: var(--main-color);
        }

        /* Payment form section */
        .payment-form {
            flex: 1;
            min-width: 300px;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .payment-form h3 {
            font-size: 1.3rem;
            margin: 30px 0 15px;
            color: #fff;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"] {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--main-color);
            outline: none;
        }

        .required {
            color: var(--main-color);
        }

        .payment-methods {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }

        .payment-method {
            flex: 1;
            min-width: 120px;
            position: relative;
        }

        .payment-method input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .payment-method label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method img {
            height: 40px;
            margin-bottom: 10px;
            object-fit: contain;
        }

        .payment-method input[type="radio"]:checked+label {
            border-color: var(--main-color);
            background: rgba(255, 44, 31, 0.1);
        }

        .payment-method label:hover {
            border-color: var(--main-color);
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-back,
        .btn-submit {
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            flex: 1;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .btn-submit {
            background: var(--main-color);
            color: #fff;
            border: none;
        }

        .btn-submit:hover {
            background: #ff3e61;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 44, 31, 0.3);
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .payment-container {
                padding: 0 15px;
            }

            .payment-wrapper {
                flex-direction: column;
            }

            .movie-details {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .movie-poster {
                width: 120px;
                margin: 0 auto 20px;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Link To Custom JS -->
    <script src="./js/main.js"></script>
</body>

</html>