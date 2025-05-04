<!DOCTYPE html>
<html lang="vi">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="./css/style.css">
	<!-- Logo web -->
	<link rel="icon" href="../img/logo_web.jpg" type="image/x-icon">
	<title>Quản lý Rạp Chiếu Phim</title>
</head>

<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand"><i class='bx bxs-movie-play icon'></i> CinemaAdmin</a>
		<ul class="side-menu">
			<li><a href="dashboard.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
			<li class="divide" data-text="main">_________________________________</li>
			<li><a href="user.php"><i class='bx bxs-user icon'></i> Quản lý tài khoản</a></li>
			<li><a href="movie.php"><i class='bx bxs-film icon'></i> Quản lý phim</a></li>
			<li><a href="room.php"><i class='bx bxs-buildings icon'></i> Quản lý phòng</a></li>
			<li><a href="shows.php"><i class='bx bxs-calendar-event icon'></i> Quản lý suất chiếu</a></li>
			<li class="divide" data-text="table and forms">_________________________________</li>
			<li><a href="invoice.php"><i class='bx bxs-receipt icon'></i> Quản lý hóa đơn</a></li>
			<li><a href="reports.php"><i class='bx bxs-report icon'></i> Báo cáo thống kê</a></li>
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu toggle-sidebar'></i>
			<form action="#">
				<div class="form-group">
					<input type="text" placeholder="Tìm kiếm...">
					<i class='bx bx-search icon'></i>
				</div>
			</form>
			<a href="#" class="nav-link">
				<i class='bx bxs-bell icon'></i>
				<span class="badge">5</span>
			</a>
			<span class="divider"></span>
			<div class="profile">
				<img src="../img/a1.jpg" alt="Admin avatar">
				<ul class="profile-link">
					<li><a href="#"><i class='bx bxs-user-circle icon'></i> Hồ sơ</a></li>
					<li><a href="#"><i class='bx bxs-cog'></i> Cài đặt</a></li>
					<li><a href="#"><i class='bx bxs-log-out-circle'></i> Đăng xuất</a></li>
				</ul>
			</div>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<h1 class="title">Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Dashboard</a></li>
			</ul>

			<!-- CARDS -->
			<div class="info-data">
				<div class="card">
					<div class="head">
						<div>
							<h2>1,850</h2>
							<p>Lượt truy cập</p>
						</div>
						<i class='bx bxs-user-plus icon'></i>
					</div>
					<span class="progress" data-value="65%"></span>
					<span class="label">Tăng 15% so với tháng trước</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>542</h2>
							<p>Vé đã bán</p>
						</div>
						<i class='bx bxs-shopping-bag icon'></i>
					</div>
					<span class="progress" data-value="80%"></span>
					<span class="label">Tăng 8% so với tuần trước</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>28</h2>
							<p>Phim đang chiếu</p>
						</div>
						<i class='bx bxs-film icon'></i>
					</div>
					<span class="progress" data-value="95%"></span>
					<span class="label">Thêm 5 phim mới trong tháng</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>35M</h2>
							<p>Doanh thu (VNĐ)</p>
						</div>
						<i class='bx bx-line-chart icon'></i>
					</div>
					<span class="progress" data-value="75%"></span>
					<span class="label">Tăng 12% so với tháng trước</span>
				</div>
			</div>

			<!-- RECENT MOVIES TABLE -->
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Danh sách phim mới nhất</h3>
						<div class="menu">
							<i class='bx bx-dots-horizontal-rounded icon'></i>
							<ul class="menu-link">
								<li><a href="movie.php"><i class='bx bx-list-ul'></i> Xem tất cả</a></li>
								<li><a href="create_movie.php"><i class='bx bx-plus'></i> Thêm phim mới</a></li>
							</ul>
						</div>
					</div>
					<div class="table-responsive">
						<table class="movie-table">
							<thead>
								<tr>
									<th>Mã phim</th>
									<th>Tên phim</th>
									<th>Thể loại</th>
									<th>Thời lượng</th>
									<th>Ngày khởi chiếu</th>
									<th>Trạng thái</th>
									<th>Thao tác</th>
								</tr>
							</thead>
							<tbody>
								<?php
								// Giả lập dữ liệu phim - trong thực tế, dữ liệu này sẽ được lấy từ database
								$movies = [
									['MP001', 'Avengers: Endgame', 'Hành động', '180 phút', '10/05/2023', 'Đang chiếu'],
									['MP002', 'Spider-Man: No Way Home', 'Hành động', '148 phút', '15/05/2023', 'Đang chiếu'],
									['MP003', 'The Batman', 'Hành động', '176 phút', '18/05/2023', 'Sắp chiếu'],
									['MP004', 'Doctor Strange 2', 'Viễn tưởng', '126 phút', '22/05/2023', 'Sắp chiếu'],
									['MP005', 'Black Panther: Wakanda Forever', 'Hành động', '161 phút', '28/05/2023', 'Sắp chiếu'],
								];

								foreach ($movies as $movie) {
									$status_class = ($movie[5] == 'Đang chiếu') ? 'success' : 'pending';
									echo "<tr>
                                        <td>{$movie[0]}</td>
                                        <td>{$movie[1]}</td>
                                        <td>{$movie[2]}</td>
                                        <td>{$movie[3]}</td>
                                        <td>{$movie[4]}</td>
                                        <td><span class='status {$status_class}'>{$movie[5]}</span></td>
                                        <td>
                                            <a href='edit_movie.php?id={$movie[0]}' class='btn-action btn-edit' title='Chỉnh sửa'>
                                                <i class='bx bx-edit'></i>
                                            </a>
                                            <a href='#' class='btn-action btn-view' title='Xem chi tiết'>
                                                <i class='bx bx-info-circle'></i>
                                            </a>
                                        </td>
                                    </tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- RECENT USERS TABLE -->
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Danh sách tài khoản mới đăng ký</h3>
						<div class="menu">
							<i class='bx bx-dots-horizontal-rounded icon'></i>
							<ul class="menu-link">
								<li><a href="user.php"><i class='bx bx-list-ul'></i> Xem tất cả</a></li>
								<li><a href="create_user.php"><i class='bx bx-plus'></i> Thêm tài khoản</a></li>
							</ul>
						</div>
					</div>
					<div class="table-responsive">
						<table class="user-table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Họ tên</th>
									<th>Email</th>
									<th>Số điện thoại</th>
									<th>Vai trò</th>
									<th>Ngày đăng ký</th>
									<th>Thao tác</th>
								</tr>
							</thead>
							<tbody>
								<?php
								// Giả lập dữ liệu người dùng - trong thực tế, dữ liệu này sẽ được lấy từ database
								$users = [
									['U001', 'Nguyễn Văn A', 'nguyenvana@gmail.com', '0901234567', 'Khách hàng', '01/05/2023'],
									['U002', 'Trần Thị B', 'tranthib@gmail.com', '0912345678', 'Khách hàng', '03/05/2023'],
									['U003', 'Lê Văn C', 'levanc@gmail.com', '0923456789', 'Nhân viên', '05/05/2023'],
									['U004', 'Phạm Thị D', 'phamthid@gmail.com', '0934567890', 'Khách hàng', '07/05/2023'],
									['U005', 'Hoàng Văn E', 'hoangvane@gmail.com', '0945678901', 'Quản trị viên', '08/05/2023'],
								];

								foreach ($users as $user) {
									$role_class = ($user[4] == 'Quản trị viên') ? 'admin' : (($user[4] == 'Nhân viên') ? 'staff' : 'customer');
									echo "<tr>
                                        <td>{$user[0]}</td>
                                        <td>{$user[1]}</td>
                                        <td>{$user[2]}</td>
                                        <td>{$user[3]}</td>
                                        <td><span class='role {$role_class}'>{$user[4]}</span></td>
                                        <td>{$user[5]}</td>
                                        <td>
                                            <a href='edit_user.php?id={$user[0]}' class='btn-action btn-edit' title='Chỉnh sửa'>
                                                <i class='bx bx-edit'></i>
                                            </a>
                                            <a href='#' class='btn-action btn-view' title='Xem chi tiết'>
                                                <i class='bx bx-info-circle'></i>
                                            </a>
                                        </td>
                                    </tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<script>
		// SIDEBAR TOGGLE
		const menuBar = document.querySelector('.toggle-sidebar');
		const sidebar = document.getElementById('sidebar');

		menuBar.addEventListener('click', function() {
			sidebar.classList.toggle('hide');
		});

		// PROFILE DROPDOWN
		const profile = document.querySelector('.profile');
		profile.addEventListener('click', function() {
			profile.classList.toggle('show');
		});

		// PROGRESS BAR
		const allProgress = document.querySelectorAll('.progress');
		allProgress.forEach(item => {
			item.style.setProperty('--value', item.dataset.value);
		});
	</script>

	<style>
		:root {
			--primary-color: #4782DA;
			--light: #F9F9F9;
			--dark: #121212;
		}

		.table-responsive {
			overflow-x: auto;
			margin-top: 1rem;
		}

		.movie-table,
		.user-table {
			width: 100%;
			border-collapse: collapse;
		}

		.movie-table th,
		.movie-table td,
		.user-table th,
		.user-table td {
			padding: 12px 15px;
			text-align: left;
			border-bottom: 1px solid rgba(132, 139, 200, 0.18);
		}

		.movie-table th,
		.user-table th {
			background-color: var(--light);
			font-weight: 600;
		}

		.movie-table tr:hover,
		.user-table tr:hover {
			background-color: rgba(132, 139, 200, 0.05);
		}

		.status,
		.role {
			padding: 6px 12px;
			border-radius: 20px;
			font-size: 12px;
			display: inline-block;
		}

		.status.success {
			background-color: rgba(23, 201, 100, 0.18);
			color: #17C964;
		}

		.status.pending {
			background-color: rgba(255, 196, 0, 0.18);
			color: #FFC400;
		}

		.role.admin {
			background-color: rgba(71, 130, 218, 0.18);
			color: #4782DA;
		}

		.role.staff {
			background-color: rgba(140, 86, 217, 0.18);
			color: #8C56D9;
		}

		.role.customer {
			background-color: rgba(0, 196, 140, 0.18);
			color: #00C48C;
		}

		.btn-action {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 32px;
			height: 32px;
			border-radius: 4px;
			background: transparent;
			border: none;
			cursor: pointer;
			transition: all 0.3s ease;
			margin-right: 5px;
		}

		.btn-edit:hover {
			background-color: rgba(71, 130, 218, 0.18);
			color: #4782DA;
		}

		.btn-view:hover {
			background-color: rgba(0, 196, 140, 0.18);
			color: #00C48C;
		}

		.info-data .card {
			transition: transform 0.3s ease;
		}

		.info-data .card:hover {
			transform: translateY(-5px);
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
		}

		.content-data {
			margin-bottom: 24px;
			padding: 20px;
			border-radius: 10px;
			background-color: var(--light);
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
		}

		@media screen and (max-width: 768px) {
			.table-responsive {
				width: 100%;
				overflow-x: auto;
			}

			.movie-table,
			.user-table {
				min-width: 800px;
			}
		}
	</style>
</body>

</html>