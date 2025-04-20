<?php

$host = 'localhost';           
$dbname = 'cinema';  
$username = 'root';            
$password = '';                

// Khởi tạo kết nối PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

   
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Kết nối thành công!"; 

} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
