<?php
include_once 'config.php';

class Crud
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = new DbConfig();
        $this->conn = $this->db->connect();
    }

    // Hàm lấy tất cả sản phẩm
    public function getProducts()
    {
        $query = "SELECT * FROM products";  // Thay đổi theo bảng sản phẩm của bạn
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hàm lấy chi tiết sản phẩm theo ID
    public function getProductById($id)
    {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
