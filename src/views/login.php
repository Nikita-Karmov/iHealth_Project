<?php include __DIR__ . '/partials/header.php'; ?>

<main class="auth-page">
    <h1>Вход</h1>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="/login">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Пароль:</label>
        <input type="password" name="password" required>

        <button type="submit">Войти</button>
    </form>

    <p>Нет аккаунта? <a href="/register">Зарегистрироваться</a></p>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
