<?php include __DIR__ . '/partials/header.php'; ?>

<main class="ordering-page">
    <h1 class="cart-title">Оформление заказа</h1>

    <form action="/cart/clear" method="post" class="order-form" onsubmit="return confirm('Подтвердить оформление заказа?');">
        <div class="form-group">
            <label for="full_name">ФИО получателя</label>
            <input type="text" id="full_name" name="full_name" required placeholder="Введите ФИО">
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input type="tel" id="phone" name="phone" required placeholder="+7 (___) ___-__-__">
        </div>

        <div class="form-group">
            <label for="address">Адрес доставки</label>
            <textarea id="address" name="address" rows="3" required placeholder="Введите адрес"></textarea>
        </div>

        <button type="submit" class="clear-cart-btn">Подтвердить заказ</button>
    </form>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
