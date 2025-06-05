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

    .account-page a {
        margin-top: 1.5rem;
        padding: 0.5rem 1rem;
        background-color: #5F6737;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .account-page a:hover {
        background-color: #0056b3;
    }
</style>

<main class="account-page">
    <h1>Личный кабинет</h1>
    <p>Имя: <?= htmlspecialchars($user['full_name']) ?></p>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <a href="/logout">Выйти</a>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
