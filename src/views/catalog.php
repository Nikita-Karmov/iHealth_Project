<?php include __DIR__ . '/partials/header.php'; ?>

<main class="catalog-page">
    <h1>Каталог товаров</h1>

    <?php if (empty($products)): ?>
        <p>Товаров пока нет.</p>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <?php if (!empty($product['image_url'])): ?>
                        <img src="/images/products/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php endif; ?>
                    <p class="price"><?= number_format($product['price'], 2) ?> руб.</p>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <form method="post" action="/cart/add">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <button type="submit" class="add-to-cart">В корзину</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
