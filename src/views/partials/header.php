<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';

// Категории
function getCategories() {
    try {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT slug, name FROM categories WHERE type IN ('основная','тематическая') ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
        error_log("DB error: " . $e->getMessage());
        return getFallbackCategories();
    }
}

function getFallbackCategories() {
    $products = require __DIR__ . '/../../1old_php/data/products.php';
    return array_map(fn($slug, $data) => ['slug' => $slug, 'name' => $data['name']], array_keys($products), $products);
}

$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>iHealth - Магазин витаминов</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="header">
    <div class="header-left">
        <a href="/" class="logo-link">
            <img src="/images/logo.png" alt="iHealth" class="logo">
        </a>
        <div class="catalog-container">
            <button class="catalog-btn" id="catalogBtn">Каталог</button>
            <div class="dropdown-menu" id="categoriesDropdown">
                <?php if (!empty($categories)): ?>
                    <?php foreach($categories as $category): ?>
                        <a href="/category?category=<?= htmlspecialchars($category['slug']) ?>" class="dropdown-item">
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="dropdown-item">Категории временно недоступны</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="icons">
        <a href="/cart" class="icon-link"><img src="/images/icons/cart.png" class="header-icon"></a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/account" class="icon-link">
                <img src="/images/icons/user.png" class="header-icon" title="Личный кабинет">
            </a>
            <a href="/logout" class="icon-link logout-link">Выйти</a>
        <?php else: ?>
            <a href="/register" class="icon-link">
                <img src="/images/icons/user.png" class="header-icon" title="Регистрация / Вход">
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="search-bar">
    <form class="search-container" method="get" action="/search">
        <input type="text" name="q" class="search-input" placeholder="Поиск витаминов и добавок...">
        <button type="submit" class="search-btn">Найти</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const catalogBtn = document.getElementById('catalogBtn');
        const dropdownMenu = document.getElementById('categoriesDropdown');

        catalogBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!dropdownMenu.contains(e.target) && !catalogBtn.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    });
</script>
