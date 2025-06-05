<?php
require_once __DIR__ . '/includes/header.php';

// Получаем slug категории из URL
$categorySlug = $_GET['category'] ?? '';

try {
    $db = Database::getConnection();
    
    // Исправленный запрос - выбираем и name, и description
    $stmt = $db->prepare("SELECT name FROM categories WHERE slug = ?");
    $stmt->execute([$categorySlug]);
    $category = $stmt->fetch();
    
    if (!$category) {
        throw new Exception("Категория не найдена");
    }
    
    // Получаем товары этой категории
    $stmt = $db->prepare("
        SELECT p.* 
        FROM products p
        JOIN product_category pc ON p.product_id = pc.product_id
        JOIN categories c ON pc.category_id = c.category_id
        WHERE c.slug = ?
        ORDER BY p.name
    ");
    $stmt->execute([$categorySlug]);
    $products = $stmt->fetchAll();
    
} catch(Exception $e) {
    error_log("Ошибка при загрузке категории: " . $e->getMessage());
    $error = "Не удалось загрузить категорию. Пожалуйста, попробуйте позже.";
}
?>

<main class="category-page">
    <?php if (isset($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php else: ?>
        <h1><?= htmlspecialchars($category['name']) ?></h1>
        
        <?php if (!empty($category['description'])): ?>
            <p class="category-description"><?= htmlspecialchars($category['description']) ?></p>
        <?php endif; ?>
        
        <div class="products-grid">
            <?php if (!empty($products)): ?>
                <?php foreach($products as $product): ?>
                    <div class="product-card">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="/images/products/<?= htmlspecialchars($product['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php endif; ?>
                        <p class="price"><?= number_format($product['price'], 2) ?> руб.</p>
                        <?php if (!empty($product['description'])): ?>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                        <?php endif; ?>
                        <button class="add-to-cart">В корзину</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>В этой категории пока нет товаров</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>