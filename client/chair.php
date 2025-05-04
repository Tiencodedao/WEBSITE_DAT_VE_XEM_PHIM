<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';
require_once __DIR__ . '/../models/Showtimes.php';
require_once __DIR__ . '/../models/Rooms.php';


// Validate required parameters
if (
    !isset($_GET['id']) || empty($_GET['id']) ||
    !isset($_GET['room']) || empty($_GET['room']) ||
    !isset($_GET['date']) || empty($_GET['date']) ||
    !isset($_GET['time']) || empty($_GET['time'])
) {
    die("Thiếu thông tin cần thiết để đặt vé.");
}

// Get parameters
$movieId = $_GET['id'];
$roomId = $_GET['room'];
$selectedDate = $_GET['date'];
$selectedTime = $_GET['time'];

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

// Get booked seats
$bookedSeats = [];
try {
    $stmt = $pdo->prepare("SELECT MaGhe FROM Ve WHERE MaSuatChieu = ?");
    $stmt->execute([$showtimeDetails['MaSuatChieu']]);
    $bookedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    $bookedSeats = [];
}

// Format date
function formatDate($date)
{
    return date('d/m/Y', strtotime($date));
}

// Ticket price
$ticketPrice = isset($showtimeDetails['GiaVe']) ? $showtimeDetails['GiaVe'] : 50000; // default
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt vé xem phim <?php echo htmlspecialchars($movie['TenPhim']); ?></title>
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

    <main class="movie-container">
        <!-- Booking Summary -->
        <div class="booking-summary">
            <h2>Thông tin đặt vé</h2>
            <div class="summary-details">
                <div class="movie-poster">
                    <img src="../admin/img/uploads/<?php echo htmlspecialchars($movie['Hinh']); ?>" alt="<?php echo htmlspecialchars($movie['TenPhim']); ?>">
                </div>
                <div class="booking-info">
                    <h3><?php echo htmlspecialchars($movie['TenPhim']); ?></h3>
                    <div class="info-row">
                        <div class="info-label">Ngày chiếu:</div>
                        <div><?php echo formatDate($selectedDate); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Giờ chiếu:</div>
                        <div><?php echo $selectedTime; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phòng chiếu:</div>
                        <div><?php echo htmlspecialchars($roomDetails['TenPhong']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seat Selection -->
        <section class="seat-selection">
            <h2>Chọn ghế ngồi</h2>

            <div class="screen-info">
                <h4 class="screen-label">Màn hình</h4>
                <div class="screen-thumb">
                    <img src="./img/screen-thumb.png" alt="Màn hình">
                </div>
            </div>

            <div class="seat-legend">
                <div class="legend-item">
                    <div class="seat-example available"></div>
                    <span>Ghế trống</span>
                </div>
                <div class="legend-item">
                    <div class="seat-example selected"></div>
                    <span>Ghế đang chọn</span>
                </div>
                <div class="legend-item">
                    <div class="seat-example booked"></div>
                    <span>Ghế đã đặt</span>
                </div>
            </div>

            <div class="seat-grid">
                <?php
                // Define rows and seats per row
                $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                $seatsPerRow = 11;

                foreach ($rows as $row) {
                    echo '<div class="seat-row">';
                    echo '<div class="row-label">' . $row . '</div>';

                    for ($i = 1; $i <= $seatsPerRow; $i++) {
                        $seatId = $row . str_pad($i, 2, '0', STR_PAD_LEFT);
                        $seatClass = in_array($seatId, $bookedSeats) ? 'seat booked' : 'seat';
                        $seatAttr = in_array($seatId, $bookedSeats) ? 'disabled' : 'onclick="selectSeat(this)"';

                        echo '<div class="' . $seatClass . '" data-seat="' . $seatId . '" ' . $seatAttr . '>' . $seatId . '</div>';
                    }

                    echo '</div>';
                }
                ?>
            </div>

            <!-- Ticket Summary -->
            <div class="ticket-summary">
                <h2>Chi tiết thanh toán</h2>
                <div class="ticket-details">
                    <div class="ticket-row">
                        <div class="ticket-label">Ghế đã chọn:</div>
                        <div class="ticket-value" id="selected_seats">Chưa chọn ghế</div>
                    </div>
                    <div class="ticket-row">
                        <div class="ticket-label">Số lượng vé:</div>
                        <div class="ticket-value" id="total_ticket">0 vé</div>
                    </div>
                    <div class="ticket-row">
                        <div class="ticket-label">Giá vé:</div>
                        <div class="ticket-value"><?php echo number_format($ticketPrice); ?> VNĐ/vé</div>
                    </div>
                    <div class="ticket-row total-row">
                        <div class="ticket-label">Tổng cộng:</div>
                        <div class="ticket-value" id="total_money">0 VNĐ</div>
                    </div>
                </div>
                <button class="btn-proceed" id="btn-checkout" onclick="proceedToCheckout()">Tiếp tục thanh toán</button>
            </div>
        </section>

        <!-- Form ẩn để submit dữ liệu -->
        <form id="checkout-form" method="post" action="payment.php" style="display: none;">
            <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">
            <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
            <input type="hidden" name="date" value="<?php echo $selectedDate; ?>">
            <input type="hidden" name="time" value="<?php echo $selectedTime; ?>">
            <input type="hidden" name="showtime_id" value="<?php echo $showtimeDetails['MaSuatChieu']; ?>">
            <input type="hidden" name="selected_seats" id="form_selected_seats" value="">
            <input type="hidden" name="total_price" id="form_total_price" value="0">
        </form>
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

    <script>
        // Define ticket price from PHP
        const ticketPrice = <?php echo $ticketPrice; ?>;
        let selectedSeats = [];

        function selectSeat(seat) {
            // Không cho phép chọn ghế đã đặt
            if (seat.classList.contains('booked')) {
                return;
            }

            seat.classList.toggle("selected");
            updateSeatInfo();
        }

        function updateSeatInfo() {
            // Cập nhật danh sách ghế đã chọn
            selectedSeats = [];
            document.querySelectorAll('.seat.selected').forEach(seat => {
                selectedSeats.push(seat.getAttribute('data-seat'));
            });

            // Hiển thị thông tin
            const totalTickets = selectedSeats.length;
            const totalMoney = totalTickets * ticketPrice;

            document.getElementById('selected_seats').innerText = selectedSeats.join(', ') || 'Chưa chọn ghế';
            document.getElementById('total_ticket').innerText = totalTickets + ' vé';
            document.getElementById('total_money').innerText = totalMoney.toLocaleString() + " VNĐ";

            // Cập nhật form ẩn
            document.getElementById('form_selected_seats').value = selectedSeats.join(',');
            document.getElementById('form_total_price').value = totalMoney;

            // Kích hoạt/vô hiệu hóa nút Tiếp tục
            document.getElementById('btn-checkout').classList.toggle('disabled', totalTickets === 0);
        }

        function proceedToCheckout() {
            if (selectedSeats.length === 0) {
                alert('Vui lòng chọn ít nhất một ghế.');
                return;
            }

            document.getElementById('checkout-form').submit();
        }

        // Khởi tạo thông tin
        updateSeatInfo();
    </script>

    <style>
        /* Main container */
        .movie-container {
            margin: 120px auto 50px;
            max-width: 1200px;
            padding: 0 20px;
        }

        /* Booking summary section */
        .booking-summary {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .booking-summary h2 {
            font-size: 1.6rem;
            margin-bottom: 25px;
            color: #fff;
            position: relative;
            padding-bottom: 12px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .booking-summary h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--main-color);
        }

        .summary-details {
            display: flex;
            gap: 30px;
            align-items: center;
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

        .booking-info {
            flex: 1;
        }

        .booking-info h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .info-row {
            display: flex;
            margin-bottom: 12px;
            align-items: center;
        }

        .info-label {
            width: 130px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            position: relative;
            padding-left: 15px;
        }

        .info-label::before {
            content: "";
            display: block;
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 16px;
            background-color: var(--main-color);
        }

        /* Seat selection section */
        .seat-selection {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            position: relative;
        }

        .seat-selection h2 {
            font-size: 1.6rem;
            margin-bottom: 25px;
            color: #fff;
            position: relative;
            padding-bottom: 12px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .seat-selection h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--main-color);
        }

        .screen-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .screen-label {
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .screen-thumb {
            width: 80%;
            margin: 0 auto;
            position: relative;
        }

        .screen-thumb img {
            width: 100%;
            filter: drop-shadow(0 5px 15px rgba(255, 255, 255, 0.2));
        }

        .seat-legend {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 30px 0 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .seat-example {
            width: 20px;
            height: 20px;
            border-radius: 5px;
        }

        .seat-example.available {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .seat-example.selected {
            background: var(--main-color);
        }

        .seat-example.booked {
            background: #666;
        }

        .seat-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 30px 0;
        }

        .seat-row {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .row-label {
            width: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
        }

        .seat {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .seat:hover:not(.booked) {
            background: var(--main-color);
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(255, 44, 31, 0.5);
        }

        .seat.selected {
            background: var(--main-color);
            color: white;
            font-weight: 600;
            box-shadow: 0 0 10px rgba(255, 44, 31, 0.5);
        }

        .seat.booked {
            background: #666;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Ticket summary */
        .ticket-summary {
            margin-top: 40px;
            background: rgba(0, 0, 0, 0.3);
            padding: 25px;
            border-radius: 10px;
        }

        .ticket-summary h2 {
            font-size: 1.4rem;
            margin-bottom: 20px;
            color: #fff;
            position: relative;
            padding-bottom: 12px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .ticket-summary h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--main-color);
        }

        .ticket-details {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ticket-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .ticket-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }

        .ticket-value {
            font-weight: 700;
            color: #fff;
            text-align: right;
        }

        .total-row {
            font-size: 1.2rem;
            color: var(--main-color);
        }

        .btn-proceed {
            width: 100%;
            padding: 15px;
            background: var(--main-color);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        .btn-proceed:hover {
            background: #ff3e61;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 44, 31, 0.3);
        }

        .btn-proceed.disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .movie-container {
                padding: 0 15px;
            }

            .summary-details {
                flex-direction: column;
                align-items: flex-start;
            }

            .movie-poster {
                width: 120px;
                margin: 0 auto 20px;
            }

            .seat {
                width: 30px;
                height: 30px;
                font-size: 0.7rem;
            }

            .seat-row {
                gap: 5px;
            }

            .row-label {
                width: 20px;
            }

            .info-label {
                width: 120px;
            }

            .seat-legend {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .seat {
                width: 24px;
                height: 24px;
                font-size: 0.6rem;
            }

            .seat-row {
                gap: 3px;
            }

            .row-label {
                width: 15px;
                font-size: 0.7rem;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Link To Custom JS -->
    <script src="./js/main.js"></script>
</body>

</html>