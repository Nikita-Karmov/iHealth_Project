<?php include __DIR__ . '/partials/header.php'; ?>

<style>
    .account-page {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 80vh;
        text-align: center;
        font-family: Arial, sans-serif;
    }

    .account-page h1 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .account-page p {
        font-size: 1.2rem;
        margin: 0.5rem 0;
    }

    .account-page a,
    .account-page button {
        margin-top: 1.5rem;
        padding: 0.5rem 1rem;
        background-color: #5F6737;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .account-page a:hover,
    .account-page button:hover {
        background-color: #0056b3;
    }

    .account-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 2rem;
        width: 300px;
    }

    .account-form input {
        margin: 0.5rem 0;
        padding: 0.5rem;
        width: 100%;
        box-sizing: border-box;
    }

    .error-message {
        color: red;
        margin-bottom: 1rem;
    }

    .order-history {
        width: 80%;
        margin: 3rem auto 0;
        font-family: Arial, sans-serif;
    }

    .order-card {
        border: 1px solid #ddd;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-radius: 10px;
        background-color: #fdfdfd;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .order-card h3 {
        margin-top: 0;
        font-size: 1.3rem;
        color: #333;
    }

    .order-info {
        font-size: 1rem;
        color: #555;
        margin-bottom: 1rem;
    }

    .order-items {
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }

    .order-items li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
        font-size: 0.95rem;
    }

    .order-items li:last-child {
        border-bottom: none;
    }

    .order-total {
        text-align: right;
        font-weight: bold;
        font-size: 1.1rem;
        margin-top: 1rem;
    }
</style>

<main class="account-page">
    <h1>Личный кабинет</h1>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <p>Имя: <?= $user && isset($user['full_name']) ? htmlspecialchars($user['full_name']) : '' ?></p>
    <p>Email: <?= $user && isset($user['email']) ? htmlspecialchars($user['email']) : '' ?></p>

    <a href="/logout">Выйти</a>

    <form action="/account/update" method="post" class="account-form">
        <h2>Редактировать профиль</h2>
        <input type="text" name="full_name" placeholder="Имя" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="password" name="password" placeholder="Новый пароль (если нужно)">
        <button type="submit">Сохранить</button>
    </form>

    <?php if (!empty($orders)): ?>
        <div class="order-history">
            <h2>История заказов</h2>

            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <h3>Заказ №<?= $order['order_id'] ?></h3>
                    <div class="order-info">
                        <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?><br>
                        Статус: <strong><?= htmlspecialchars($order['status']) ?></strong>
                    </div>

                    <ul class="order-items">
                        <?php foreach ($order['items'] as $item): ?>
                            <li>
                                <?= htmlspecialchars($item['name']) ?> — 
                                <?= $item['quantity'] ?> шт. × <?= number_format($item['price_at_purchase'], 2, ',', ' ') ?> ₽
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="order-total">
                        Итого: <?= number_format($order['total_amount'], 2, ',', ' ') ?> ₽
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="margin-top: 2rem;">У вас пока нет заказов.</p>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
