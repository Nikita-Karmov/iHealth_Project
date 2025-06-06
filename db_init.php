<?php
// Правильный путь к файлу db.php (из корня проекта)
require_once __DIR__ . '/config/db.php';

$products = [
    'omega3' => ['name' => 'Омега-3', 'type' => 'основная'],
    'zhelezo' => ['name' => 'Железо', 'type' => 'основная'],
    'yod' => ['name' => 'Йод', 'type' => 'основная'],
    'magnesium' => ['name' => 'Магний', 'type' => 'основная'],
    'vitamin-d' => ['name' => 'Витамин D', 'type' => 'основная'],
    'vitamin-c' => ['name' => 'Витамин C', 'type' => 'основная'],
    'forwoman' => ['name' => 'Комплекс для женщин', 'type' => 'тематическая'],
    'forman' => ['name' => 'Комплекс для мужчин', 'type' => 'тематическая'],
];

try {
    $db = Database::getConnection();
    
    // Отключаем проверку внешних ключей для очистки
    $db->exec("SET session_replication_role = 'replica'");
    $db->exec("TRUNCATE TABLE product_category, categories RESTART IDENTITY CASCADE");
    $db->exec("SET session_replication_role = 'origin'");
    
    $stmt = $db->prepare("INSERT INTO categories (slug, name, type) VALUES (:slug, :name, :type)");
    
    // foreach ($products as $slug => $data) {
    //     $stmt->execute([
    //         ':slug' => $slug,
    //         ':name' => $data['name'],
    //         ':type' => $data['type']
    //     ]);
    // }
    
    // echo "База данных успешно инициализирована! Добавлено " . count($products) . " категорий.";
    
} catch(Exception $e) {
    echo "Ошибка: " . $e->getMessage();
    error_log("DB init error: " . $e->getMessage());
}