CREATE DATABASE Cinema;

USE Cinema;

-- Table for Phim (Movies)
CREATE TABLE Phim (
    MaPhim VARCHAR(50) PRIMARY KEY,
    TenPhim VARCHAR(255),
    TheLoai VARCHAR(255),
    DaoDien VARCHAR(255),
    DiemDanhGia VARCHAR(255),
    ThoiLuong VARCHAR(255),
    NgayKhoiChieu DATE,
    MoTa TEXT,
    DoTuoi INT,
    TrangThai VARCHAR(255)
);
-- Table for Phong (Rooms)
CREATE TABLE Phong (
    MaPhong VARCHAR(50) PRIMARY KEY,
    TenPhong VARCHAR(255),
    SoLuongGhe INT
);
-- Table for SuatChieu (Showtimes)
CREATE TABLE SuatChieu (
    MaSuatChieu VARCHAR(50) PRIMARY KEY,
    MaPhim VARCHAR(50),
    MaPhong VARCHAR(50),
    NgayChieu DATE,
    GioiThieu TIME,
    giaVe FLOAT,
    FOREIGN KEY (MaPhim) REFERENCES Phim(MaPhim),
    FOREIGN KEY (MaPhong) REFERENCES Phong(MaPhong)
);

-- Table for Ve (Tickets)
CREATE TABLE Ve (
    MaVe VARCHAR(50) PRIMARY KEY,
    MaPhim VARCHAR(50),
    GiaVe FLOAT,
    FOREIGN KEY (MaPhim) REFERENCES Phim(MaPhim)
);

-- Table for NguoiDung (Users)
CREATE TABLE NguoiDung (
    MaNguoiDung INT AUTO_INCREMENT PRIMARY KEY,
    HoTen VARCHAR(255),
    NgaySinh DATE,
    GioiTinh VARCHAR(10),
    Email VARCHAR(255),
    SoDienThoai VARCHAR(20),
    MatKhau VARCHAR(255),
    VaiTro VARCHAR(50),
    DiaChi VARCHAR(255),
    UNIQUE (Email)
);

-- Table for NhanVien (Employees)
CREATE TABLE NhanVien (
    MaNhanVien VARCHAR(50) PRIMARY KEY,
    VaiTro VARCHAR(255),
    Email VARCHAR(255)
);

-- Table for HoaDon (Invoice)
CREATE TABLE HoaDon (
    MaHoaDon INT AUTO_INCREMENT PRIMARY KEY,
    MaNguoiDung INT,
    MaPhong VARCHAR(50),
    MaSuatChieu VARCHAR(50),
    SoLuong INT,
    ThanhTien FLOAT,
    FOREIGN KEY (MaNguoiDung) REFERENCES NguoiDung(MaNguoiDung),
    FOREIGN KEY (MaSuatChieu) REFERENCES SuatChieu(MaSuatChieu)
);

-- Table for KhuyenMai (Discounts)
CREATE TABLE KhuyenMai (
    MaKhuyenMai VARCHAR(50) PRIMARY KEY,
    ChiTiet TEXT,
    TenKhuyenMai VARCHAR(255)
);

-- Table for GoiDichVu (Services)
CREATE TABLE GoiDichVu (
    MaDichVu VARCHAR(50) PRIMARY KEY,
    TenDichVu VARCHAR(255),
    GiaTien INT,
    ChiTiet TEXT
);

-- Table for Ghe (Seats)
CREATE TABLE Ghe (
    MaGhe VARCHAR(50) PRIMARY KEY,
    MaPhong VARCHAR(50),
    SoLuong INT,
    TrangThai VARCHAR(255),
    FOREIGN KEY (MaPhong) REFERENCES Phong(MaPhong)
);



-- Sample function to apply discount in HoaDon table
DELIMITER $$

CREATE FUNCTION ApDungKhuyenMai(MaHoaDon INT)
RETURNS FLOAT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE ThanhTien FLOAT;
    DECLARE KhuyenMai FLOAT;
    
    -- Get the total amount from HoaDon table
    SELECT ThanhTien INTO ThanhTien
    FROM HoaDon
    WHERE MaHoaDon = MaHoaDon;
    
    -- Get the discount value from KhuyenMai table
    SELECT GiaTien INTO KhuyenMai
    FROM KhuyenMai
    WHERE MaKhuyenMai = (SELECT MaKhuyenMai FROM HoaDon WHERE MaHoaDon = MaHoaDon);
    
    -- Return the amount after applying the discount
    RETURN ThanhTien - KhuyenMai;
END$$

DELIMITER ;
