<?php include __DIR__ . '/partials/header.php'; ?>

<main class="auth-page">
    <h1>Регистрация</h1>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="/register">
        <label for="full_name">Имя:</label>
        <input type="text" name="full_name" id="full_name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Зарегистрироваться</button>
    </form>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
