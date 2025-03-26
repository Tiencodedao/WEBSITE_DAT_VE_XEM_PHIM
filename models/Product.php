<?php
require_once __DIR__ . '/../config/config.php';

class Product
{
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT products.*, categories.name AS category_name 
                             FROM products 
                             JOIN categories ON products.category_id = categories.id");
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($name, $status, $description, $content, $banner, $image, $category_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO products 
            (name, status, description, content, banner, image, category_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        return $stmt->execute([$name, $status, $description, $content, $banner, $image, $category_id]);
    }

    public static function update($id, $name, $status, $description, $content, $banner, $image, $category_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE products SET 
            name = ?, status = ?, description = ?, content = ?, banner = ?, image = ?, 
            category_id = ?, updated_at = NOW() 
            WHERE id = ?");
        return $stmt->execute([$name, $status, $description, $content, $banner, $image, $category_id, $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
