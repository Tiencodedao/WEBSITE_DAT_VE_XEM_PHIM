<?php
require_once __DIR__ . '/../config/config.php';

class Category
{
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($name, $status = 'active')
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO categories (name, status, created_at, updated_at) 
        VALUES (?, ?, NOW(), NOW())");
        return $stmt->execute([$name, $status]);
    }

    public static function update($id, $name, $status)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, status = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$name, $status, $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
