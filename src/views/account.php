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
</style>

<main class="account-page">
    <h1>Личный кабинет</h1>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <p>Имя: <?= $user['full_name'] ?></p> 
    <p>Email: <?= $user['email'] ?></p>
    
    <!-- вот как защитить  -->
    <!-- <p>Имя: <?= htmlspecialchars($user['full_name']) ?></p>  -->
    <!--<p>Email: <?= htmlspecialchars($user['email']) ?></p> -->
    <a href="/logout">Выйти</a>

    <form action="/account/update" method="post" class="account-form">
        <h2>Редактировать профиль</h2>
        <input type="text" name="full_name" placeholder="Имя" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="password" name="password" placeholder="Новый пароль (если нужно)">
        <button type="submit">Сохранить</button>
    </form>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
