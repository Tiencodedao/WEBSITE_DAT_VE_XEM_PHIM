<?php
include_once '../model/crud.php';

$crud = new Crud();

// Lấy tất cả sản phẩm
$products = $crud->getProducts();

// Nếu có ID trong URL, lấy chi tiết sản phẩm
if (isset($_GET['id'])) {
    $product = $crud->getProductById($_GET['id']);
    include '../view/productdetail.php';  // Hiển thị chi tiết sản phẩm
} else {
    include '../view/product.php';  // Hiển thị danh sách sản phẩm
}
