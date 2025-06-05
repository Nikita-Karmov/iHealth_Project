<?php include __DIR__ . '/partials/header.php'; ?>

<main class="cart-page">
    <h1 class="cart-title">Ваша корзина</h1>

    <?php if (empty($items)): ?>
        <p class="empty-message">Корзина пуста.</p>
    <?php else: ?>
        <form action="/cart/clear" method="post" onsubmit="return confirm('Очистить корзину?');">
            <button type="submit" class="clear-cart-btn">Очистить корзину</button>
        </form>

        <div class="cart-items">
            <?php foreach ($items as $item): ?>
                <?php
                    $qty = isset($item['quantity']) && is_numeric($item['quantity']) ? (int)$item['quantity'] : 1;
                    $productId = (int)($item['product_id'] ?? 0);
                    $name = htmlspecialchars($item['name'] ?? 'Товар');
                    $image = htmlspecialchars($item['image_url'] ?? 'placeholder.png');
                    $price = number_format($item['price'] ?? 0, 2);
                ?>
                <div class="cart-item">
                    <img src="/images/products/<?= $image ?>" width="80" alt="<?= $name ?>">
                    <div class="cart-item-details">
                        <h3><?= $name ?></h3>
                        <p><?= $price ?> руб.</p>

                        <form action="/cart/update" method="post" class="quantity-form">
                            <input type="hidden" name="product_id" value="<?= $productId ?>">

                            <div class="quantity-controls">
                                <button type="submit" name="quantity" value="<?= max(1, $qty - 1) ?>" class="qty-btn">−</button>
                                <span class="qty-value"><?= $qty ?></span>
                                <button type="submit" name="quantity" value="<?= $qty + 1 ?>" class="qty-btn">+</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <form action="/cart/clear" method="post" onsubmit="return confirm('Оформить заказ?');">
            <button type="submit" class="clear-cart-btn">Оформить заказ</button>
        </form>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
