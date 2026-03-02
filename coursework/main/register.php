<?php
require_once __DIR__ . '/includes/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($login) || empty($email) || empty($password)) {
        $error = "Заполните все поля!";
    } elseif ($password !== $password_confirm) {
        $error = "Пароли не совпадают!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            $error = "Логин занят!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (login, email, password) VALUES (?, ?, ?)")->execute([$login, $email, $hashed]);
            header("Location: login.php?success=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-container">
    <h2>Регистрация</h2>
    <?php if ($error): ?> <div class="error-msg"><?= $error ?></div> <?php endif; ?>
    <form method="POST">
        <div class="input-group"><input type="text" name="login" placeholder="Логин" required></div>
        <div class="input-group"><input type="email" name="email" placeholder="Email" required></div>
        <div class="input-group">
            <div class="password-wrapper">
                <input type="password" name="password" id="p1" placeholder="Пароль" required>
                <span class="toggle-btn" onclick="toggle('p1')">👁️</span>
            </div>
        </div>
        <div class="input-group">
            <div class="password-wrapper">
                <input type="password" name="password_confirm" id="p2" placeholder="Повтор пароля" required>
                <span class="toggle-btn" onclick="toggle('p2')">👁️</span>
            </div>
        </div>
        <button type="submit">Создать аккаунт</button>
    </form>
    <div class="footer-link">Уже есть аккаунт? <a href="login.php">Войти</a></div>
</div>
<script>
function toggle(id){
    const i = document.getElementById(id);
    i.type = i.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
