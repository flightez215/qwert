<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}

$display_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $counts = array_count_values($_SESSION['cart']);
    $ids = implode(',', array_keys($counts));
    $stmt = $pdo->query("SELECT * FROM products WHERE id_products IN ($ids)");
    
    while ($row = $stmt->fetch()) {
        $id = $row['id_products'];
        $quantity = $counts[$id];
        $subtotal = $row['price'] * $quantity;
        $total_price += $subtotal;

        $display_items[] = [
            'id' => $id,
            'name' => $row['name'],
            'price' => $row['price'],
            'qty' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина — ООО «Кверт»</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="display: block;">

<header style="background: #fff; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center;">
    <a href="index.php" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
        <img src="assets/img/logo.png" alt="ООО Кверт" style="height: 40px; width: auto; object-fit: contain;">
        <h2 style="margin: 0; color: #ff6600; font-family: 'Segoe UI', sans-serif;">ООО «Кверт»</h2>
    </a>
    <nav><a href="index.php" style="text-decoration: none; font-weight: bold; color: #ff6600;">← Назад в каталог</a></nav>
</header>

<div class="cart-wrapper">
    <h1>Корзина покупок</h1>

    <?php if (empty($display_items)): ?>
        <div style="text-align: center; padding: 50px;">
            <div style="font-size: 60px; margin-bottom: 20px;">🛒</div>
            <h3>Ваша корзина пуста</h3>
            <p style="color: #666;">Посмотрите наш ассортимент техники и выберите что-нибудь подходящее.</p>
            <br>
            <a href="index.php" style="text-decoration:none;"><button class="btn-buy" style="width:auto; padding: 12px 30px;">Перейти к покупкам</button></a>
        </div>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена за ед.</th>
                    <th>Кол-во</th>
                    <th>Итого</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($display_items as $item): ?>
                <tr>
                    <td class="cart-item-info">
                        <b><?= htmlspecialchars($item['name']) ?></b>
                        <small>Артикул: <?= $item['id'] ?></small>
                    </td>
                    <td><?= number_format($item['price'], 0, '.', ' ') ?> ₽</td>
                    <td><?= $item['qty'] ?> шт.</td>
                    <td><b><?= number_format($item['subtotal'], 0, '.', ' ') ?> ₽</b></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-footer">
            <a href="?action=clear" class="btn-clear-cart" onclick="return confirm('Очистить корзину ООО «Кверт»?')">Очистить список</a>
            
            <div style="text-align: right;">
                <div class="total-label">Общая стоимость:</div>
                <div class="total-sum"><?= number_format($total_price, 0, '.', ' ') ?> ₽</div>
                <br>
                <a href="checkout.php" style="text-decoration: none;">
                    <button class="btn-checkout-main">Перейти к оформлению</button>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
