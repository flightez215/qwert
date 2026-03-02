<?php
session_start();
require_once __DIR__ . '/includes/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$_POST['login']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: profile.php");
        exit;
    } else { $error = "Неверный логин или пароль!"; }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-container">
    <h2>Вход</h2>
    <?php if (isset($_GET['success'])): ?> <div class="success-msg">Регистрация успешна!</div> <?php endif; ?>
    <?php if ($error): ?> <div class="error-msg"><?= $error ?></div> <?php endif; ?>
    <form method="POST">
        <div class="input-group"><input type="text" name="login" placeholder="Логин" required></div>
        <div class="input-group">
            <div class="password-wrapper">
                <input type="password" name="password" id="lp" placeholder="Пароль" required>
                <span class="toggle-btn" onclick="toggle('lp')">👁️</span>
            </div>
        </div>
        <button type="submit">Войти</button>
    </form>
    <div class="footer-link">Нет аккаунта? <a href="register.php">Регистрация</a></div>
</div>
<script>function toggle(id){ const i = document.getElementById(id); i.type = i.type === "password" ? "text" : "password"; }</script>
</body>
</html>
