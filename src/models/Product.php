<?php
namespace App\Models;

use Database;
use PDO;

class Product {
    public static function getByCategorySlug($slug) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT p.* 
            FROM products p
            JOIN product_category pc ON p.product_id = pc.product_id
            JOIN categories c ON pc.category_id = c.category_id
            WHERE c.slug = ?
            ORDER BY p.name
        ");
        $stmt->execute([$slug]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM products ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search($query) {
    $db = Database::getConnection();
    $stmt = $db->prepare("
        SELECT * FROM products
        WHERE name ILIKE ? OR description ILIKE ?
        ORDER BY name
    ");
    $q = '%' . $query . '%';
    $stmt->execute([$q, $q]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
