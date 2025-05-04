<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/movie.css">
    <title>AdminSite</title>
</head>

<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bxs-smile icon'></i> Movie Admin</a>
        <ul class="side-menu">
            <li><a href="index.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li class="divide" data-text="main">_________________________________</li>
            <li>
                <a href="user.php"><i class='bx bxs-inbox icon'></i> Quản lý tài khoản <i class='bx bx-chevron-right icon-right'></i></a>
            </li>
            <li><a href="movie.php"><i class='bx bxs-chart icon'></i> Quản Lý phim</a></li>
            <li><a href="room.php"><i class='bx bxs-widget icon'></i> Quản Lý Phòng</a></li>
            <li class="divide" data-text="table and forms">_________________________________</li>
            <li><a href="bill.php"><i class='bx bx-table icon'></i> Quản lý hóa đơn </a></li>
            <li>
                <a href="show.php"><i class='bx bxs-notepad icon'></i> Quản lý suất chiếu </a>
            </li>
        </ul>
    </section>
    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
            <form action="#">
                <div class="form-group">
                    <input type="text" placeholder="Search...">
                    <i class='bx bx-search icon'></i>
                </div>
            </form>
            <a href="#" class="nav-link">
                <i class='bx bxs-bell icon'></i>
                <span class="badge">5</span>
            </a>
            <a href="#" class="nav-link">
                <i class='bx bxs-message-square-dots icon'></i>
                <span class="badge">8</span>
            </a>
            <span class="divider"></span>
            <div class="profile">
                <img src="../img/a1.jpg" alt="">
                <ul class="profile-link">
                    <li><a href="#"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                    <li><a href="#"><i class='bx bxs-cog'></i> Settings</a></li>
                    <li><a href="#"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                </ul>
            </div>
        </nav>
        <!-- NAVBAR -->