<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';
require_once __DIR__ . '/../models/Rooms.php';
require_once __DIR__ . '/../models/Showtimes.php';

// Kiểm tra xem có tham số 'id' trong URL không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID phim không hợp lệ.");
}

// Lấy thông tin bộ phim từ ID
$movieId = $_GET['id'];
$movie = Movie::find($movieId);

// Nếu không tìm thấy phim, hiển thị thông báo lỗi
if (!$movie) {
    die("Không tìm thấy phim.");
}

// Fix lỗi: Kiểm tra cả hai khóa MaTL và MATL
$maTL = isset($movie['MaTL']) ? $movie['MaTL'] : (isset($movie['MATL']) ? $movie['MATL'] : null);

// Khởi tạo biến relatedMovies là mảng rỗng
$relatedMovies = [];
// Chỉ gọi hàm getByGenre nếu tìm thấy mã thể loại
if ($maTL !== null) {
    try {
        $relatedMovies = Movie::getByGenre($maTL, $movieId, 4);
    } catch (Exception $e) {
        // Xử lý ngoại lệ nếu có
        $relatedMovies = [];
    }
}

// Lấy tất cả ngày chiếu có sẵn cho phim này
$availableDates = Showtime::getDates($movieId);

// Các tham số từ URL (nếu có)
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$selectedRoomId = isset($_GET['room']) ? $_GET['room'] : null;
$selectedTime = isset($_GET['time']) ? $_GET['time'] : null;

// Lấy danh sách phòng chiếu
$rooms = Room::all();

// Lấy danh sách phòng có suất chiếu vào ngày đã chọn
$roomIdsWithShowtimes = [];
$stmt = $pdo->prepare("SELECT DISTINCT MaPhong FROM SuatChieu WHERE MaPhim = ? AND NgayChieu = ?");
$stmt->execute([$movieId, $selectedDate]);
$roomIdsWithShowtimes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Nếu không có phòng nào được chọn hoặc phòng đã chọn không có suất chiếu, chọn phòng đầu tiên có suất chiếu
if (empty($selectedRoomId) || !in_array($selectedRoomId, $roomIdsWithShowtimes)) {
    $selectedRoomId = !empty($roomIdsWithShowtimes) ? $roomIdsWithShowtimes[0] : null;
}

// Lấy tên phòng đã chọn
$selectedRoomName = '';
if (!empty($selectedRoomId)) {
    foreach ($rooms as $room) {
        if ($room['MaPhong'] == $selectedRoomId) {
            $selectedRoomName = $room['TenPhong'];
            break;
        }
    }
}

// Lấy các suất chiếu cho phòng và ngày đã chọn
$showtimes = [];
if (!empty($selectedRoomId)) {
    $showtimes = Showtime::getTimes($movieId, $selectedRoomId, $selectedDate);
}

// Hàm định dạng ngày
function formatDate($date)
{
    return date('d/m/Y', strtotime($date));
}

// Kiểm tra nếu đây là yêu cầu AJAX
$isAjaxRequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Nếu là yêu cầu AJAX, chỉ trả về phần HTML cần thiết
if ($isAjaxRequest) {
    $responseType = isset($_GET['part']) ? $_GET['part'] : '';

    // Trả về nội dung phòng chiếu
    if ($responseType === 'rooms') {
        ob_start();
        foreach ($rooms as $room):
            $roomId = $room['MaPhong'];
            $isAvailable = in_array($roomId, $roomIdsWithShowtimes);
            $roomClass = ($isAvailable ? ($roomId == $selectedRoomId ? 'active' : '') : 'disabled');
?>
            <?php if ($isAvailable): ?>
                <a href="javascript:void(0)"
                    onclick="selectRoom('<?php echo $roomId; ?>')"
                    class="room-item <?php echo $roomClass; ?>">
                    <?php echo htmlspecialchars($room['TenPhong']); ?>
                </a>
            <?php else: ?>
                <div class="room-item <?php echo $roomClass; ?>">
                    <?php echo htmlspecialchars($room['TenPhong']); ?>
                </div>
            <?php endif; ?>
            <?php endforeach;
        echo ob_get_clean();
        exit;
    }

    // Trả về nội dung giờ chiếu
    else if ($responseType === 'times') {
        ob_start();
        if (!empty($showtimes)):
            foreach ($showtimes as $time):
                $timeClass = ($time == $selectedTime) ? 'time-slot active' : 'time-slot';
            ?>
                <a href="javascript:void(0)"
                    onclick="selectTime('<?php echo $time; ?>')"
                    class="<?php echo $timeClass; ?>">
                    <?php echo $time; ?>
                </a>
            <?php endforeach;
        else: ?>
            <p>Không có lịch chiếu cho phòng này</p>
        <?php endif;
        echo ob_get_clean();
        exit;
    }

    // Trả về thông tin đặt vé
    else if ($responseType === 'booking-info') {
        ob_start();
        if (!empty($selectedDate) && !empty($selectedRoomId) && !empty($selectedTime)): ?>
            <h3>Thông tin đặt vé</h3>
            <div class="info-row">
                <div class="info-label">Phim:</div>
                <div><?php echo htmlspecialchars($movie['TenPhim']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Ngày chiếu:</div>
                <div><?php echo formatDate($selectedDate); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Phòng chiếu:</div>
                <div><?php echo htmlspecialchars($selectedRoomName); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Giờ chiếu:</div>
                <div><?php echo $selectedTime; ?></div>
            </div>
            <a href="chair.php?id=<?php echo $movieId; ?>&room=<?php echo $selectedRoomId; ?>&date=<?php echo $selectedDate; ?>&time=<?php echo $selectedTime; ?>"
                class="btn-proceed">Tiếp tục</a>
<?php endif;
        echo ob_get_clean();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['TenPhim']); ?></title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/detail.css">
    <!-- Logo web -->
    <link rel="icon" href="./img/logo_web.jpg" type="image/x-icon">
    <!-- Box Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- jQuery để dễ dàng sử dụng AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

    <!-- Main Content -->
    <main class="movie-container">
        <section class="movie-header">
            <!-- Ảnh phim bên trái -->
            <div class="movie-image">
                <img src="../admin/img/uploads/<?php echo htmlspecialchars($movie['Hinh']); ?>" alt="<?php echo htmlspecialchars($movie['TenPhim']); ?>">
            </div>

            <!-- Thông tin phim bên phải -->
            <div class="movie-details">
                <h1><?php echo htmlspecialchars($movie['TenPhim']); ?></h1>

                <div class="movie-meta">
                    <div class="meta-item">
                        <i class="bx bx-calendar"></i>
                        <span><?php echo isset($movie['NgayKhoiChieu']) ? date('d/m/Y', strtotime($movie['NgayKhoiChieu'])) : 'Đang cập nhật'; ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="bx bx-time"></i>
                        <span>120 phút</span>
                    </div>
                    <div class="meta-item">
                        <i class="bx bx-film"></i>
                        <span><?php echo isset($movie['TENTL']) ? htmlspecialchars($movie['TENTL']) : htmlspecialchars($movie['TheLoai']); ?></span>
                    </div>
                </div>

                <div class="info-list">
                    <?php if (isset($movie['DaoDien']) && !empty($movie['DaoDien'])): ?>
                        <div class="info-item">
                            <div class="info-label">Đạo diễn:</div>
                            <div><?php echo htmlspecialchars($movie['DaoDien']); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($movie['DienVien']) && !empty($movie['DienVien'])): ?>
                        <div class="info-item">
                            <div class="info-label">Diễn viên:</div>
                            <div><?php echo htmlspecialchars($movie['DienVien']); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($movie['QuocGia']) && !empty($movie['QuocGia'])): ?>
                        <div class="info-item">
                            <div class="info-label">Quốc gia:</div>
                            <div><?php echo htmlspecialchars($movie['QuocGia']); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($movie['NgonNgu']) && !empty($movie['NgonNgu'])): ?>
                        <div class="info-item">
                            <div class="info-label">Ngôn ngữ:</div>
                            <div><?php echo htmlspecialchars($movie['NgonNgu']); ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Nút mua vé và xem trailer -->
                <div class="action-buttons">
                    <a href="javascript:void(0)" onclick="scrollToElement('#showtimes')" class="btn-buy-ticket">Mua vé ngay</a>
                    <a href="javascript:void(0)" onclick="scrollToElement('#trailer')" class="btn-trailer">
                        <i class="bx bx-play-circle"></i> Xem trailer
                    </a>
                </div>
            </div>
        </section>

        <!-- Mô tả phim -->
        <section class="movie-description">
            <h2>Nội dung phim</h2>
            <?php if (isset($movie['MoTa']) && !empty($movie['MoTa'])): ?>
                <p><?php echo nl2br(htmlspecialchars($movie['MoTa'])); ?></p>
            <?php else: ?>
                <p>Đang cập nhật nội dung phim...</p>
            <?php endif; ?>
        </section>

        <!-- Lịch chiếu phim -->
        <section class="showtimes" id="showtimes">
            <h2>Lịch chiếu</h2>

            <!-- Bước 1: Chọn ngày (sử dụng JavaScript) -->
            <div class="booking-step">
                <div class="step-title">
                    <span class="step-number">1</span>
                    <h3>Chọn ngày xem phim</h3>
                </div>
                <div class="date-selector">
                    <?php
                    $today = date('Y-m-d');
                    $ngayKhoiChieu = $movie['NgayKhoiChieu']; // Lấy ngày khởi chiếu từ phim
                    $weekdays = [
                        1 => 'Thứ 2',
                        2 => 'Thứ 3',
                        3 => 'Thứ 4',
                        4 => 'Thứ 5',
                        5 => 'Thứ 6',
                        6 => 'Thứ 7',
                        7 => 'Chủ Nhật'
                    ];

                    // Tính ngày Thứ 2 tuần hiện tại
                    $monday = date('Y-m-d', strtotime('monday this week'));

                    // Hiển thị 7 ngày từ Thứ 2 đến Chủ Nhật
                    for ($i = 0; $i < 7; $i++) {
                        $date = date('Y-m-d', strtotime("+$i days", strtotime($monday)));
                        $dayLabel = $weekdays[$i + 1];

                        $isAvailable = in_array($date, $availableDates) && ($date >= $ngayKhoiChieu);

                        // Class CSS
                        $class = 'date-item' . ($date == $selectedDate ? ' active' : '') . (!$isAvailable ? ' disabled' : '');

                        // Chỉ tạo link nếu ngày có suất chiếu
                        if ($isAvailable) {
                            echo "<a href='javascript:void(0)' onclick='selectDate(\"$date\")' class='$class'>";
                            echo "$dayLabel<br><small>" . date('d/m', strtotime($date)) . "</small>";
                            echo "</a>";
                        } else {
                            echo "<div class='$class'>";
                            echo "$dayLabel<br><small>" . date('d/m', strtotime($date)) . "</small>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- Bước 2: Chọn phòng (sử dụng JavaScript) -->
            <div class="booking-step">
                <div class="step-title">
                    <span class="step-number">2</span>
                    <h3>Chọn phòng chiếu</h3>
                </div>
                <div class="room-selector" id="room-container">
                    <?php foreach ($rooms as $room): ?>
                        <?php
                        $roomId = $room['MaPhong'];
                        $isAvailable = in_array($roomId, $roomIdsWithShowtimes);
                        $roomClass = ($isAvailable ? ($roomId == $selectedRoomId ? 'active' : '') : 'disabled');
                        ?>
                        <?php if ($isAvailable): ?>
                            <a href="javascript:void(0)"
                                onclick="selectRoom('<?php echo $roomId; ?>')"
                                class="room-item <?php echo $roomClass; ?>">
                                <?php echo htmlspecialchars($room['TenPhong']); ?>
                            </a>
                        <?php else: ?>
                            <div class="room-item <?php echo $roomClass; ?>">
                                <?php echo htmlspecialchars($room['TenPhong']); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Bước 3: Chọn giờ (sử dụng JavaScript) -->
            <div class="booking-step">
                <div class="step-title">
                    <span class="step-number">3</span>
                    <h3>Chọn giờ chiếu</h3>
                </div>
                <div class="time-container">
                    <div class="time-slots" id="time-container">
                        <?php if (!empty($showtimes)): ?>
                            <?php foreach ($showtimes as $time): ?>
                                <?php $timeClass = ($time == $selectedTime) ? 'time-slot active' : 'time-slot'; ?>
                                <a href="javascript:void(0)"
                                    onclick="selectTime('<?php echo $time; ?>')"
                                    class="<?php echo $timeClass; ?>">
                                    <?php echo $time; ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có lịch chiếu cho phòng này</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Nút tiếp tục (chỉ hiển thị khi đã chọn đủ thông tin) -->
            <div class="booking-step" id="booking-info-container">
                <div class="booking-info">
                    <?php if (!empty($selectedDate) && !empty($selectedRoomId) && !empty($selectedTime)): ?>
                        <h3>Thông tin đặt vé</h3>
                        <div class="info-row">
                            <div class="info-label">Phim:</div>
                            <div><?php echo htmlspecialchars($movie['TenPhim']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Ngày chiếu:</div>
                            <div><?php echo formatDate($selectedDate); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phòng chiếu:</div>
                            <div><?php echo htmlspecialchars($selectedRoomName); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Giờ chiếu:</div>
                            <div><?php echo $selectedTime; ?></div>
                        </div>
                        <a href="chair.php?id=<?php echo $movieId; ?>&room=<?php echo $selectedRoomId; ?>&date=<?php echo $selectedDate; ?>&time=<?php echo $selectedTime; ?>"
                            class="btn-proceed">Tiếp tục</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Trailer phim -->
            <section class="trailer-container" id="trailer">
                <h2>Trailer phim</h2>
                <div class="trailer-frame">
                    <?php if (isset($movie['Trailer']) && !empty($movie['Trailer'])): ?>
                        <iframe src="<?php echo htmlspecialchars($movie['Trailer']); ?>" allowfullscreen></iframe>
                    <?php else: ?>
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" allowfullscreen></iframe>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Phim liên quan -->
            <?php if (!empty($relatedMovies)): ?>
                <section class="related-movies">
                    <h2>Phim cùng thể loại</h2>
                    <div class="related-container">
                        <?php foreach ($relatedMovies as $relMovie): ?>
                            <a href="detail.php?id=<?php echo $relMovie['MaPhim']; ?>" class="related-item">
                                <div class="related-image">
                                    <img src="../admin/img/uploads/<?php echo htmlspecialchars($relMovie['Hinh']); ?>" alt="<?php echo htmlspecialchars($relMovie['TenPhim']); ?>">
                                </div>
                                <h3 class="related-title"><?php echo htmlspecialchars($relMovie['TenPhim']); ?></h3>
                                <div class="related-genre"><?php echo htmlspecialchars($relMovie['TENTL']); ?></div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
    </main>

    <!-- NewsLetter -->
    <section class="newsletter" id="newsletter">
        <h2>Subscribe To Get <br> Newsletter</h2>
        <form action="">
            <input type="email" class="email" placeholder="Enter Email..." required>
            <input type="submit" value="Subscribe" class="btn">
        </form>
    </section>

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

    <!-- CSS cho lịch chiếu -->
    <style>
        /* Các bước đặt vé */
        .booking-step {
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
        }

        .step-title {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .step-number {
            background: var(--main-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }

        .step-title h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        /* Chọn ngày */
        .date-selector {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .date-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .date-item.active {
            background: var(--main-color);
            color: white;
        }

        .date-item.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Chọn phòng */
        .room-selector {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .room-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .room-item.active {
            background: var(--main-color);
            color: white;
        }

        .room-item.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Chọn giờ */
        .time-container {
            margin-top: 15px;
        }

        .time-slots {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .time-slot {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .time-slot:hover {
            background: var(--main-color);
            color: white;
        }

        .time-slot.active {
            background: var(--main-color);
            color: white;
        }

        /* Thông tin đặt vé */
        .booking-info {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 8px;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        .btn-proceed {
            background: var(--main-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
        }

        .btn-proceed:hover {
            background: #ff3e61;
        }

        /* Điều chỉnh cho nút mua vé */
        .btn-buy-ticket,
        .btn-trailer {
            display: inline-block;
            text-decoration: none;
            cursor: pointer;
        }

        /* Hiệu ứng loading */
        .loading {
            position: relative;
            opacity: 0.7;
        }

        .loading:after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
        }
    </style>

    <!-- JavaScript cho AJAX -->
    <script>
        // Biến toàn cục để lưu trữ lựa chọn của người dùng
        let currentMovieId = '<?php echo $movieId; ?>';
        let currentDate = '<?php echo $selectedDate; ?>';
        let currentRoom = '<?php echo $selectedRoomId; ?>';
        let currentTime = '<?php echo $selectedTime; ?>';

        // Hàm chọn ngày
        function selectDate(date) {
            // Reset giờ đã chọn
            currentTime = '';

            // Hiển thị hiệu ứng loading
            $('.date-selector .date-item').removeClass('active');
            $('.date-selector .date-item').each(function() {
                if ($(this).text().includes(date)) {
                    $(this).addClass('active');
                }
            });

            $('#room-container').addClass('loading');
            $('#time-container').addClass('loading');

            // Cập nhật URL mà không reload trang
            window.history.pushState({}, '', `detail.php?id=${currentMovieId}&date=${date}`);

            // Cập nhật biến toàn cục
            currentDate = date;

            // Lấy danh sách phòng cho ngày đã chọn qua AJAX
            $.ajax({
                url: `detail.php?id=${currentMovieId}&date=${date}&part=rooms`,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#room-container').html(response).removeClass('loading');

                    // Sau khi cập nhật phòng, lấy giờ chiếu cho phòng đầu tiên nếu có
                    const firstRoomLink = $('#room-container a.room-item').first();
                    if (firstRoomLink.length) {
                        const roomId = firstRoomLink.attr('onclick').match(/'([^']+)'/)[1];
                        selectRoom(roomId);
                    } else {
                        $('#time-container').html('<p>Không có lịch chiếu cho ngày này</p>').removeClass('loading');
                        $('#booking-info-container .booking-info').html('');
                    }
                },
                error: function() {
                    $('#room-container').html('<p>Đã xảy ra lỗi khi tải dữ liệu</p>').removeClass('loading');
                    $('#time-container').html('<p>Không có lịch chiếu cho ngày này</p>').removeClass('loading');
                }
            });
        }

        // Hàm chọn phòng
        function selectRoom(roomId) {
            // Reset giờ đã chọn
            currentTime = '';

            // Hiển thị hiệu ứng loading
            $('#room-container .room-item').removeClass('active');
            $(`#room-container a[onclick*="${roomId}"]`).addClass('active');

            $('#time-container').addClass('loading');

            // Cập nhật URL mà không reload trang
            window.history.pushState({}, '', `detail.php?id=${currentMovieId}&date=${currentDate}&room=${roomId}`);

            // Cập nhật biến toàn cục
            currentRoom = roomId;

            // Lấy danh sách giờ chiếu cho phòng đã chọn qua AJAX
            $.ajax({
                url: `detail.php?id=${currentMovieId}&date=${currentDate}&room=${roomId}&part=times`,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#time-container').html(response).removeClass('loading');
                    $('#booking-info-container .booking-info').html('');
                },
                error: function() {
                    $('#time-container').html('<p>Đã xảy ra lỗi khi tải dữ liệu</p>').removeClass('loading');
                }
            });
        }

        // Hàm chọn giờ
        function selectTime(time) {
            // Hiển thị hiệu ứng loading
            $('#time-container .time-slot').removeClass('active');
            $(`#time-container a:contains("${time}")`).addClass('active');

            $('#booking-info-container').addClass('loading');

            // Cập nhật URL mà không reload trang
            window.history.pushState({}, '', `detail.php?id=${currentMovieId}&date=${currentDate}&room=${currentRoom}&time=${time}`);

            // Cập nhật biến toàn cục
            currentTime = time;

            // Lấy thông tin đặt vé qua AJAX
            $.ajax({
                url: `detail.php?id=${currentMovieId}&date=${currentDate}&room=${currentRoom}&time=${time}&part=booking-info`,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#booking-info-container .booking-info').html(response);
                    $('#booking-info-container').removeClass('loading');
                },
                error: function() {
                    $('#booking-info-container .booking-info').html('<p>Đã xảy ra lỗi khi tải thông tin</p>');
                    $('#booking-info-container').removeClass('loading');
                }
            });
        }

        // Xử lý nút back trên trình duyệt
        window.onpopstate = function(event) {
            location.reload();
        };

        // Hàm cuộn mượt đến phần tử
        function scrollToElement(selector) {
            const element = document.querySelector(selector);
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    </script>
</body>

</html>