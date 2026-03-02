<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Доступ запрещен. Только для администрации ООО «Кверт».");
}
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id_products = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Ошибка: Товар с ID $id не найден в базе ООО «Кверт».");
    }
} else {
    header("Location: admin.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $desc = trim($_POST['description']);
    $cat_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $image = !empty($_POST['image']) ? trim($_POST['image']) : 'no-image.jpg';

    try {
        $sql = "UPDATE products SET name = ?, price = ?, description = ?, category_id = ?, image = ? WHERE id_products = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $price, $desc, $cat_id, $image, $id]);
        header("Location: admin.php?updated=1");
        exit;
    } catch (PDOException $e) {
        $error = "Ошибка при сохранении: " . $e->getMessage();
    }
}
$cats = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование товара — ООО «Кверт»</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .edit-card { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.1); }
        .edit-card label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; margin-top: 15px; }
        .edit-card input, .edit-card select, .edit-card textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-group { margin-top: 30px; display: flex; gap: 10px; }
    </style>
</head>
<body style="display: block; background: #f4f7f6;">

<div class="edit-card">
    <h2 style="margin-top: 0;">Редактирование товара #<?= $id ?></h2>
    
    <?php if (isset($error)): ?>
        <div style="background: #ffebe8; color: #d03910; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Название товара</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Категория</label>
        <select name="category_id">
            <option value="">-- Без категории --</option>
            <?php foreach($cats as $c): ?>
                <option value="<?= $c['id_category'] ?>" <?= ($c['id_category'] == $product['category_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Цена (₽)</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" step="0.01" required>

        <label>Имя файла изображения (в папке assets/img/)</label>
        <input type="text" name="image" value="<?= htmlspecialchars($product['image']) ?>" placeholder="например: monitor.jpg">

        <label>Техническое описание</label>
        <textarea name="description" rows="6"><?= htmlspecialchars($product['description']) ?></textarea>

        <div class="btn-group">
            <button type="submit" name="save_product" style="background: #28a745; color: #fff; border: none; padding: 12px 25px; border-radius: 6px; cursor: pointer; flex: 2; font-weight: bold;">
                Сохранить в базу ООО «Кверт»
            </button>
            <a href="admin.php" style="background: #6c757d; color: #fff; text-decoration: none; padding: 12px 25px; border-radius: 6px; flex: 1; text-align: center;">
                Отмена
            </a>
        </div>
    </form>
</div>

</body>
</html>
