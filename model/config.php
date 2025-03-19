<?php
class dbconfig
{
    private $host = "127.0.0.1";   // Địa chỉ máy chủ
    private $name = "root";    // Tên người dùng cơ sở dữ liệu
    private $pass = "123456";  // Mật khẩu cơ sở dữ liệu
    private $db = "Cinema";   // Tên cơ sở dữ liệu
    protected $conn = null;   // Biến lưu trữ kết nối

    // Phương thức khởi tạo
    public function __construct()
    {
        try {
            // Kết nối với cơ sở dữ liệu sử dụng PDO
            $this->conn = new PDO("mysql:host=$this->host; dbname=$this->db; charset=utf8", $this->name, $this->pass);
            echo "Kết nối đến cơ sở dữ liệu thành công!";
        } catch (PDOException $e) {
            echo "Không thể kết nối đến cơ sở dữ liệu: " . $e->getMessage();
        }
    }

    // Phương thức để lấy kết nối
    public function getConnection()
    {
        return $this->conn;
    }
}

// Tạo đối tượng và kết nối đến cơ sở dữ liệu
$db = new dbconfig();
