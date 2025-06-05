<?php include __DIR__ . '/partials/header.php'; ?>

<main class="auth-page">
    <link rel="stylesheet" href="/css/auth.css">
    <h1>Авторизация / Регистрация</h1>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="auth-forms">
        <form action="/login" method="post" class="auth-form">
            <h2>Вход</h2>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>

        <form action="/register" method="post" class="auth-form">
            <h2>Регистрация</h2>
            <input type="text" name="full_name" placeholder="Имя" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
