<?php include __DIR__ . '/partials/header.php'; ?>

<main class="category-page">
    <?php if (!empty($error)): ?>
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
                            <img src="/images/products/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php endif; ?>
                        <p class="price"><?= number_format($product['price'], 2) ?> руб.</p>
                        <?php if (!empty($product['description'])): ?>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                        <?php endif; ?>
                        <form method="post" action="/cart/add">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <button type="submit" class="add-to-cart">В корзину</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>В этой категории пока нет товаров</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
