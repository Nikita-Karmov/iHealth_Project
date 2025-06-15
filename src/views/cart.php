<?php include __DIR__ . '/partials/header.php'; ?>

<style>
.cart-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    font-family: Arial, sans-serif;
}

.cart-title {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.empty-message {
    text-align: center;
    font-size: 1.2rem;
    color: #777;
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.cart-item img {
    border-radius: 5px;
}

.cart-item-details {
    flex: 1;
}

.cart-item-details h3 {
    margin: 0 0 0.5rem;
    font-size: 1.2rem;
}

.cart-item-details p {
    margin: 0.2rem 0;
    font-size: 1rem;
}

.quantity-form {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.qty-btn {
    padding: 0.3rem 0.7rem;
    font-size: 1rem;
    background-color: #5F6737;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.qty-btn:hover {
    background-color: #4a512b;
}

.remove-btn {
    margin-top: 0.5rem;
    background-color: #cc3333;
    padding: 0.3rem 0.7rem;
    border: none;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}

.remove-btn:hover {
    background-color: #a62828;
}

.cart-total {
    font-weight: bold;
    text-align: right;
    font-size: 1.3rem;
    margin-bottom: 2rem;
}

.clear-cart-btn {
    display: inline-block;
    padding: 0.7rem 1.2rem;
    background-color: #5F6737;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    margin: 0.3rem;
}

.clear-cart-btn:hover {
    background-color: #4a512b;
}
</style>

<main class="cart-page">
    <h1 class="cart-title">Ваша корзина</h1>

    <?php if (empty($items)): ?>
        <p class="empty-message">Корзина пуста.</p>
    <?php else:
        $total = 0;
    ?>

        <form action="/cart/clear" method="post" onsubmit="return confirm('Очистить корзину?');">
            <button type="submit" class="clear-cart-btn">Очистить корзину</button>
        </form>

        <div style="margin-bottom: 1rem;">
            <a href="/cart?sort=asc" class="clear-cart-btn">Сначала дешёвые</a>
            <a href="/cart?sort=desc" class="clear-cart-btn">Сначала дорогие</a>
        </div>

        <div class="cart-items">
            <?php foreach ($items as $item):
                $qty = isset($item['quantity']) && is_numeric($item['quantity']) ? (int)$item['quantity'] : 1;
                $productId = (int)($item['product_id'] ?? 0);
                $name = htmlspecialchars($item['name'] ?? 'Товар');
                $image = htmlspecialchars($item['image_url'] ?? 'placeholder.png');
                $price = (float)($item['price'] ?? 0);
                $subtotal = $price * $qty;
                $total += $subtotal;
            ?>
                <div class="cart-item">
                    <img src="/images/products/<?= $image ?>" width="80" alt="<?= $name ?>">
                    <div class="cart-item-details">
                        <h3><?= $name ?></h3>
                        <p>Цена: <?= number_format($price, 2) ?> руб. × <?= $qty ?> = <strong><?= number_format($subtotal, 2) ?> руб.</strong></p>

                        <form action="/cart/update" method="post" class="quantity-form">
                            <input type="hidden" name="product_id" value="<?= $productId ?>">
                            <button type="submit" name="quantity" value="<?= max(1, $qty - 1) ?>" class="qty-btn">−</button>
                            <span class="qty-value"><?= $qty ?></span>
                            <button type="submit" name="quantity" value="<?= $qty + 1 ?>" class="qty-btn">+</button>
                        </form>

                        <form action="/cart/remove" method="post" onsubmit="return confirm('Удалить этот товар из корзины?');">
                            <input type="hidden" name="product_id" value="<?= $productId ?>">
                            <button type="submit" class="remove-btn">Удалить</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-total">
            <h2>Итого: <?= number_format($total, 2) ?> руб.</h2>
        </div>

    <a href="/ordering" class="clear-cart-btn" style="display:inline-block; text-align:center;">
        Перейти к оформлению
    </a>


    <?php endif; ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
