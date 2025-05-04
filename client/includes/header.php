<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Movies.php';
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
        <a href="./index.html" class="logo">
            <i class="bx bx-movie"></i> Movies
        </a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <!-- Menu -->
        <ul class="navbar">
            <li><a href="./index.html" class="home-active">Trang chủ</a></li>
            <li><a href="./movie.php">Phim</a></li>
        </ul>
        <a href="./login.html" class="btn">Đăng nhập</a>
    </header>