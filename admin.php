<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Доступ запрещен. Админ-панель ООО «Кверт».");
}

if (isset($_POST['add_category'])) {
    $c_name = trim($_POST['category_name']);
    if (!empty($c_name)) {
        $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)")->execute([$c_name]);
        header("Location: admin.php");
        exit;
    }
}

if (isset($_GET['del_cat'])) {
    try {
        $pdo->prepare("DELETE FROM categories WHERE id_category = ?")->execute([$_GET['del_cat']]);
    } catch (Exception $e) {
        $error_cat = "Нельзя удалить категорию, пока в ней есть товары!";
    }
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $desc = trim($_POST['description']);
    $cat_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    
    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, category_id, image) VALUES (?, ?, ?, ?, 'no-image.jpg')");
    $stmt->execute([$name, $price, $desc, $cat_id]);
    header("Location: admin.php?added=1");
    exit;
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM products WHERE id_products = ?")->execute([$_GET['delete']]);
    header("Location: admin.php");
    exit;
}

$cats = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();
$products = $pdo->query("SELECT p.*, c.category_name 
                         FROM products p 
                         LEFT JOIN categories c ON p.category_id = c.id_category 
                         ORDER BY p.id_products DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель — ООО «Кверт»</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="admin-body">

<div class="admin-container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>ИС «ООО «Кверт»» (Админ)</h1>
        <nav>
            <a href="index.php">На сайт</a> | 
            <a href="profile.php">Профиль</a> |
            <a href="logout.php" style="color:red;">Выход</a>
        </nav>
    </div>

    <div class="admin-form-box" style="margin-top:20px; border-left: 5px solid #ffc107;">
        <h3>Категории товаров</h3>
        <form method="POST" style="display: flex; gap: 10px; margin-bottom: 15px;">
            <input type="text" name="category_name" placeholder="Название новой категории" required style="flex:1; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
            <button type="submit" name="add_category" style="width: auto; padding: 0 20px; background:#ffc107; color:black; border:none; border-radius:5px; cursor:pointer;">Создать</button>
        </form>
        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <?php foreach($cats as $c): ?>
                <span style="background: #eee; padding: 5px 12px; border-radius: 20px; font-size: 13px; display: flex; align-items: center;">
                    <?= htmlspecialchars($c['category_name']) ?> 
                    <a href="?del_cat=<?= $c['id_category'] ?>" style="color:red; text-decoration:none; margin-left:8px; font-weight:bold;" onclick="return confirm('Удалить категорию?')">&times;</a>
                </span>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="admin-form-box">
        <h3>Добавить новую технику</h3>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="name" placeholder="Наименование (например, Клавиатура ООО «Кверт»)" required>
            </div>
            <div class="input-group">
                <select name="category_id" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #dddfe2; background: white;">
                    <option value="">-- Выберите категорию --</option>
                    <?php foreach($cats as $c): ?>
                        <option value="<?= $c['id_category'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <input type="number" name="price" placeholder="Цена (руб.)" required>
            </div>
            <textarea name="description" rows="4" placeholder="Технические характеристики техники..." style="width:100%; border: 1px solid #dddfe2; border-radius: 6px; padding: 10px; font-family: inherit; margin-bottom: 15px;"></textarea>
            <div class="input-group">
                <input type="text" name="image" placeholder="Имя файла картинки (например: rtx4060.jpg)" value="<?= isset($product) ? $product['image'] : 'no-image.jpg' ?>">
            </div>
            <button type="submit" name="add_product" style="background: #28a745; color:white; border:none; padding:12px; border-radius:6px; width:100%; cursor:pointer; font-weight:bold;">Внести в базу ООО «Кверт»</button>
        </form>
    </div>

    <h3>Текущий ассортимент компании</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Категория</th>
                <th>Наименование</th>
                <th>Цена</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td>#<?= $p['id_products'] ?></td>
                <td><span style="color: #666; font-size: 13px;"><?= htmlspecialchars($p['category_name'] ?? 'Без категории') ?></span></td>
                <td><b><?= htmlspecialchars($p['name']) ?></b></td>
                <td><?= number_format($p['price'], 0, '.', ' ') ?> ₽</td>
                <td>
                    <a href="edit.php?id=<?= $p['id_products'] ?>" style="color: #007bff; text-decoration: none; margin-right: 15px; font-size: 14px; font-weight: bold;">Изменить</a>
                    <a href="?delete=<?= $p['id_products'] ?>" class="btn-delete" onclick="return confirm('Удалить товар?')">Удалить</a>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
