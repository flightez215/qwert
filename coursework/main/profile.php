<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['user_role'] = $user['role'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет — ИС Продажа техники</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="profile-wrapper">

<div class="profile-card">
    <div class="profile-avatar">
        <?= strtoupper(substr($user['login'], 0, 1)) ?>
    </div>
    
    <h2>Добро пожаловать!</h2>
    <p style="color: #65676b; margin-bottom: 20px;">Ваш персональный профиль в ИС</p>

    <div class="user-info">
        <div class="info-item"><b>Логин:</b> <?= htmlspecialchars($user['login']) ?></div>
        <div class="info-item"><b>Email:</b> <?= htmlspecialchars($user['email']) ?></div>
        <div class="info-item"><b>Статус:</b> 
            <span style="color: <?= ($user['role'] === 'admin' ? '#d9534f' : '#ff6600') ?>; font-weight: bold;">
                <?= ($user['role'] === 'admin' ? 'Администратор' : 'Покупатель') ?>
            </span>
        </div>
    </div>
    <?php if ($user['role'] === 'admin'): ?>
        <a href="admin.php" class="admin-link">⚙️ Панель управления</a>
    <?php endif; ?>

    <a href="index.php" style="text-decoration: none;">
        <button type="button" style="background: #28a745; margin-bottom: 10px;">Перейти в магазин</button>
    </a>

    <a href="logout.php" class="logout-link">Выйти из аккаунта</a>
</div>

</body>
</html>
