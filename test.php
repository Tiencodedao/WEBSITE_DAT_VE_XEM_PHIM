<?php
require_once __DIR__ . '../models/Product.php';

$movies = Product::all(); // hoặc giới hạn 10 phim đầu tiên nếu cần
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moves Website</title>
    <link rel="stylesheet" href="client/css/style.css">
    <!-- Box Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

</head>

<body>
    <!-- Navbar -->
    <header>
        <a href="./index.html" class="logo">
            <i class="bx bx-movie"></i> Movies
        </a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <!-- Menu -->
        <ul class="navbar">
            <li><a href="./index.html" class="home-active">Home</a></li>
            <li><a href="./move.html">Movies</a></li>
            <!-- <li><a href="#coming">Coming</a></li>
          <li><a href="#newsletter">Newsletter</a></li> -->
        </ul>
        <a href="#" class="btn">Sign In</a>
    </header>
    <!-- Home -->
    <section class="home swiper" id="home">
        <div class="swiper-wrapper">
            <div class="swiper-slide container">
                <img src="client/img/home1.jpg" alt="Movie Image">
                <div class="home-text">
                    <span>Marvel Universe</span>
                    <h1>Venom: Let There <br> Be Carnage</h1>
                    <a href="#" class="btn">Book Now</a>
                    <a href="#" class="play">
                        <i class="bx bx-play"></i>
                    </a>
                </div>
            </div>
            <!-- Box 2 -->
            <div class="swiper-slide container">
                <img src="client/img/home2.jpg" alt="">
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
                <img src="client/img/home3.jpg" alt="">
                <div class="home-text">
                    <span>Marvel Universe</span>
                    <h1>Spider-Man: <br> Far from Home</h1>
                    <a href="#" class="btn">Book Now</a>
                    <a href="#" class="play">
                        <i class="bx bx-play"></i>
                    </a>
                </div>
            </div>

        </div>

        <div class="swiper-pagination"></div>
    </section>
    <section class="movies" id="movies">
        <h2 class="heading">Phim Đang Chiếu</h2>
        <div class="movies-container">

            <?php foreach ($movies as $movie): ?>
                <div class="box">
                    <div class="box-img">
                        <img src="client/img/<?= basename($movie['image']) ?>" alt="" title="abc">

                    </div>
                    <h3><?= htmlspecialchars($movie['name']) ?></h3>
                    <span>120 min | <?= $movie['status'] ?></span> <!-- Có thể là thể loại, hoặc dùng trường khác -->
                </div>
            <?php endforeach; ?>

        </div>
    </section>
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
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Link To Custom JS -->
    <script src="client/main.js"></script>
</body>

</html>