<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';

// Lấy tất cả phim từ database
$movies = Movie::all();

// Nếu người dùng đã chọn lọc
if (isset($_GET['filter']) && $_GET['filter'] !== '') {
    $selectedGenre = $_GET['filter'];
    $filteredMovies = array_filter($movies, function ($movie) use ($selectedGenre) {
        return $movie['TENTL'] === $selectedGenre;
    });
    // Gán lại danh sách phim đã lọc
    $movies = $filteredMovies;
}

// Nhóm phim theo thể loại - CHUYỂN XUỐNG SAU KHI LỌC
$grMovies = [];
foreach ($movies as $movie) {
    $grMovies[$movie['TENTL']][] = $movie;
}

$theLoais = array_column(Movie::getAllGenres(), 'TENTL');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="./css/style.css">
    <!-- Box Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Logo web -->
    <link rel="icon" href="./img/logo_web.jpg" type="image/x-icon">

</head>

<body>
    <!-- Navbar không thay đổi -->
    <header>
        <a href="./index.html" class="logo">
            <i class="bx bx-movie"></i> Movies
        </a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <!-- Menu -->
        <ul class="navbar">
            <li><a href="./index.php">Trang chủ</a></li>
            <li><a href="./movie.php" class="home-active">Phim</a></li>
        </ul>
        <a href="./login.html" class="btn">Đăng nhập</a>
    </header>

    <!-- Home section không thay đổi -->
    <section class="home swiper" id="home">
        <!-- Nội dung swiper không thay đổi -->
        <div class="swiper-wrapper">
            <!-- Các slide vẫn giữ nguyên -->
            <div class="swiper-slide container">
                <img src="img/home3.jpg" alt="Movie Image">
                <div class="home-text">
                    <span>Marvel Universe</span>
                    <h1>Spider-Man: <br>Far from Home</h1>
                    <a href="#" class="btn">Book Now</a>
                    <a href="#" class="play">
                        <i class="bx bx-play"></i>
                    </a>
                </div>
            </div>
            <!-- Box 2 -->
            <div class="swiper-slide container">
                <img src="img/home2.jpg" alt="">
                <div class="home-text">
                    <span>Marvel Universe</span>
                    <h1>Avengers: <br> Infinity War</h1>
                    <a href="#" class="btn">Book Now</a>
                    <a href="#" class="play">
                        <i class="bx bx-play"></i>
                    </a>
                </div>
            </div>
            <!-- Box 3 -->
            <div class="swiper-slide container">
                <img src="img/txx.jpg" alt="">
                <div class="home-text">
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </section>

    <!--Form lọc -->
    <section class="newsletter filter-form-container">
        <h2>Chọn thể loại phim bạn muốn xem</h2>
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="filter-form">
            <input type="text" value="Lọc theo thể loại:" disabled class="filter-label">
            <select name="filter" id="filter" class="filter-select">
                <option value="">Tất cả</option>
                <?php foreach ($theLoais as $tl): ?>
                    <option value="<?php echo htmlspecialchars($tl); ?>" <?php echo (isset($_GET['filter']) && $_GET['filter'] === $tl) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tl); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="LỌC" class="btn filter-button">
        </form>
    </section>

    <!-- Section hiển thị phim -->
    <section class="movies" id="movies">
        <?php if (empty($grMovies)): ?>
            <h2 class="heading">Không tìm thấy phim phù hợp</h2>
        <?php else: ?>
            <?php foreach ($grMovies as $tenTheLoai => $dsPhim): ?>
                <h2 class="heading">Danh sách phim <?php echo htmlspecialchars($tenTheLoai); ?></h2>
                <div class="movies-container">
                    <?php foreach ($dsPhim as $movie): ?>
                        <div class="box">
                            <div class="box-img">
                                <a href="detail.php?id=<?php echo $movie['MaPhim']; ?>">
                                    <img src="../admin/img/uploads/<?php echo htmlspecialchars($movie['Hinh']); ?>" alt="">
                                </a>
                            </div>
                            <h3><?php echo htmlspecialchars($movie['TenPhim']); ?></h3>
                            <span>120 min | <?php echo htmlspecialchars($movie['TENTL']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <!-- NewsLetter không thay đổi -->
    <section class="newsletter" id="newsletter">
        <h2>Subscribe To Get <br> Newsletter</h2>
        <form action="">
            <input type="email" class="email" placeholder="Enter Email..." required>
            <input type="submit" value="Subscribe" class="btn">
        </form>
    </section>

    <!-- Footer không thay đổi -->
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

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Link To Custom JS -->
    <script src="./js/main.js"></script>
</body>

</html>