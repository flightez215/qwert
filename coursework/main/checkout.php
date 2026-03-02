<?php
session_start();
require_once __DIR__ . '/includes/db.php';

// Если корзина пуста и форма не отправлена — возвращаем в магазин
if (empty($_SESSION['cart']) && !isset($_POST['place_order'])) {
    header("Location: index.php");
    exit;
}

$total_sum = 0;
$success = false;

// Расчет итоговой суммы для вывода (пока корзина не очищена)
if (!empty($_SESSION['cart'])) {
    $counts = array_count_values($_SESSION['cart']);
    $ids = implode(',', array_keys($counts));
    $stmt = $pdo->query("SELECT price, id_products FROM products WHERE id_products IN ($ids)");
    while ($row = $stmt->fetch()) {
        $total_sum += $row['price'] * $counts[$row['id_products']];
    }
}

// Обработка подтверждения заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $fio = trim($_POST['fio']);
    $phone = trim($_POST['phone']);
    $pay_method = $_POST['payment'];

    if (!empty($fio) && !empty($phone)) {
        // Очищаем корзину ООО «Кверт»
        unset($_SESSION['cart']);
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оформление заказа — ООО «Кверт»</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="display: block; background: #f0f2f5;">

<header style="background: #fff; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center;">
    <a href="index.php" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
        <img src="assets/img/logo.png" alt="ООО Кверт" style="height: 40px; width: auto; object-fit: contain;">
        <h2 style="margin: 0; color: #ff6600; font-family: 'Segoe UI', sans-serif;">ООО «Кверт»</h2>
    </a>

    <nav><a href="index.php" style="text-decoration: none; font-weight: bold;">← В магазин</a></nav>
</header>

<div class="container">
    <?php if ($success): ?>
        <!-- ЗАГЛУШКА УСПЕШНОГО ЗАКАЗА -->
        <div class="success-card">
            <div class="success-icon">✔</div>
            <h1>Заказ успешно принят!</h1>
            <p style="background: #f0f2f5; padding: 10px; border-radius: 30px; font-weight: bold; color: #ff6600;">
                Номер заказа: #<?= rand(1000, 9999) ?>
            </p>
            <p>Уважаемый <b><?= htmlspecialchars($fio) ?></b>, ваш запрос передан в отдел логистики ООО «Кверт».</p>
            <p>Ожидайте звонка на номер <b><?= htmlspecialchars($phone) ?></b> для подтверждения времени доставки.</p>
            <br>
            <a href="index.php" style="text-decoration:none;"><button class="btn-buy" style="width: auto; padding: 12px 40px;">Вернуться к покупкам</button></a>
        </div>
    <?php else: ?>
        <!-- ФОРМА ОФОРМЛЕНИЯ -->
        <div class="checkout-wrapper">
            <h1 style="margin-top:0;">Оформление заказа</h1>
            
            <div class="checkout-summary" style="background: #e7f3ff; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                <span style="color: #555;">Сумма к оплате:</span><br>
                <b style="font-size: 24px; color: #1c1e21;"><?= number_format($total_sum, 0, '.', ' ') ?> ₽</b>
            </div>

            <form method="POST" class="checkout-form">
                <label>ФИО получателя</label>
                <input type="text" name="fio" placeholder="Например: Петров Алексей" required>

                <label>Контактный телефон</label>
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>

                <label>Адрес доставки</label>
                <textarea name="address" rows="2" placeholder="Укажите ваш город и адрес" required style="width:100%; border:1px solid #dddfe2; border-radius:6px; padding:10px; margin-bottom:20px;"></textarea>

                <label>Выберите способ оплаты</label>
                <select name="payment">
                    <option value="cash">Наличными при получении в ООО «Кверт»</option>
                    <option value="card_courier">Картой курьеру</option>
                    <option value="online">Оплата онлайн (через ИС банка)</option>
                </select>

                <button type="submit" name="place_order" class="btn-checkout-main" style="width: 100%;">
                    Подтвердить покупку
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<footer style="text-align: center; padding: 40px; color: #888; font-size: 13px;">
    &copy; <?= date('Y') ?> Информационная система ООО «Кверт»
</footer>

</body>
</html>
