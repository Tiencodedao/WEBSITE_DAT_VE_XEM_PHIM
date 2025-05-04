<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';

// Lấy tất cả phim từ database
$movies = Movie::all();
$nowPlayingMovies = Movie::getNowPlaying();
$comingSoonMovies = Movie::getComingSoon();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang chủ</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/footer.css">
  <!-- Box Icons -->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <!-- Link Swiper's CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <!-- Logo web -->
  <link rel="icon" href="./img/logo_web.jpg" type="image/x-icon">

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
      <li><a href="./index.php" class="home-active">Trang chủ</a></li>
      <li><a href="./movie.php">Phim</a></li>
      <!-- <li><a href="#coming">Coming</a></li>
          <li><a href="#newsletter">Newsletter</a></li> -->
    </ul>
    <a href="./login.php" class="btn">Đăng nhập</a>
  </header>
  <!-- Home -->
  <section class="home swiper" id="home">
    <div class="swiper-wrapper">
      <div class="swiper-slide container">
        <img src="img/home3.jpg" alt="Movie Image">
        <div class="home-text">
          <span>Marvel Universe</span>
          <h1>Spider-Man: <br>Far from Home</h1>
          <a href="#" class="btn">Book Now</a>
          <a href="https://youtu.be/JfVOs4VSpmA?si=7Hx_MQHuAztXA5g1" class="play">
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
      <!-- Box 3  -->
      <div class="swiper-slide container">
        <img src="img/txx.jpg" alt="">
        <div class="home-text">
        </div>
      </div>

    </div>
    <div class="swiper-pagination"></div>
  </section>
  <!-- Movies -->
  <!-- Movies -->
  <section class="movies" id="movies">
    <h2 class="heading">Phim Đang Chiếu</h2>
    <div class="movies-container">
      <?php
      $count = 0;
      foreach ($nowPlayingMovies as $movie):
        if ($count >= 10) break;
      ?>
        <div class="box">
          <div class="box-img">
            <a href="detail.php?id=<?php echo $movie['MaPhim']; ?>">
              <img src="../admin/img/uploads/<?php echo htmlspecialchars($movie['Hinh']); ?>" alt="">
            </a>
          </div>
          <h3><?php echo htmlspecialchars($movie['TenPhim']); ?></h3>
          <span>120 min | <?php echo htmlspecialchars($movie['TENTL']); ?></span>
        </div>
        <?php $count++; ?>
      <?php endforeach; ?>
    </div>
  </section>


  <!-- Coming container -->
  <!-- Coming container -->
  <section class="coming" id="coming">
    <h2 class="heading">Phim Sắp Chiếu</h2>
    <div class="coming-container swiper">
      <div class="swiper-wrapper">
        <?php
        $count = 0;
        foreach ($comingSoonMovies as $movie):
          if ($count >= 10) break;
        ?>
          <div class="swiper-slide box">
            <div class="box-img">
              <a href="detail.php?id=<?php echo $movie['MaPhim']; ?>">
                <img src="../admin/img/uploads/<?php echo htmlspecialchars($movie['Hinh']); ?>" alt="">
              </a>
            </div>
            <h3><?php echo htmlspecialchars($movie['TenPhim']); ?></h3>
            <span>120 min | <?php echo htmlspecialchars($movie['TENTL']); ?></span>
          </div>
          <?php $count++; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>


  <!-- NewsLetter -->
  <section class="newsletter" id="newsletter">
    <h2>Subscribe To Get <br> Newsletter</h2>
    <form action="">
      <input type="email" class="email" placeholder="Enter Email..." required>
      <input type="submit" value="Subscribe" class="btn">
    </form>
  </section>
  <!-- Footer -->
  <!-- <section class="footer">
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
  </div> -->
  <!-- Footer -->
  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <!-- Logo and About -->
      <div class="footer-section about">
        <a href="#" class="logo">
          <i class="bx bxs-movie"></i> Movies
        </a>
        <p class="footer-about">Nền tảng đặt vé xem phim trực tuyến hàng đầu với đa dạng thể loại phim và rạp chiếu khắp Việt Nam.</p>
        <div class="social">
          <a href="https://www.facebook.com/" title="Facebook"><i class='bx bxl-facebook'></i></a>
          <a href="https://x.com/" title="Twitter"><i class='bx bxl-twitter'></i></a>
          <a href="https://www.instagram.com/" title="Instagram"><i class='bx bxl-instagram'></i></a>
          <a href="https://www.tiktok.com/en/" title="TikTok"><i class='bx bxl-tiktok'></i></a>
        </div>
      </div>

      <!-- Liên kết -->
      <div class="footer-section links">
        <h3>Liên kết nhanh</h3>
        <ul>
          <li><a href="./index.html">Trang chủ</a></li>
          <li><a href="./movie.php">Phim</a></li>
          <li><a href="#coming">Phim sắp chiếu</a></li>
          <li><a href="./about.html">Về chúng tôi</a></li>
          <li><a href="./contact.html">Liên hệ</a></li>
        </ul>
      </div>

      <!-- Thể loại -->
      <div class="footer-section categories">
        <h3>Thể loại phim</h3>
        <ul>
          <li><a href="#">Hành động</a></li>
          <li><a href="./movie.php?genre=comedy">Hài hước</a></li>
          <li><a href="./movie.php?genre=horror">Kinh dị</a></li>
          <li><a href="./movie.php?genre=romance">Tình cảm</a></li>
          <li><a href="./movie.php?genre=adventure">Phiêu lưu</a></li>
        </ul>
      </div>

      <!-- Liên hệ -->
      <div class="footer-section contact">
        <h3>Liên hệ với chúng tôi</h3>
        <ul>
          <li><i class='bx bxs-map'></i> 123 Nguyễn Văn Linh, Quận 7, TP.HCM</li>
          <li><i class='bx bxs-phone'></i> +84 123 456 789</li>
          <li><i class='bx bxs-envelope'></i> info@moviesvietnam.com</li>
          <li><i class='bx bxs-time'></i> Thứ 2 - Chủ nhật: 8:00 - 22:00</li>
        </ul>
      </div>
      <!-- Copyright -->
      <div class="copyright">
        <p>&#169; 2025 Movies Vietnam. Tất cả quyền được bảo lưu.</p>
      </div>
  </footer>
  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!-- Link To Custom JS -->
  <script src="./js/main.js"></script>
</body>

</html>