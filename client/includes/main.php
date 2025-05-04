<?php
// Lấy tất cả phim từ database
$movies = Movie::all();
$nowPlayingMovies = Movie::getNowPlaying();
$comingSoonMovies = Movie::getComingSoon();
?>

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