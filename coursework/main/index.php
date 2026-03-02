<?php
session_start();
require_once __DIR__ . '/includes/db.php';
$cat_filter    = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$search_query  = isset($_GET['search']) ? trim($_GET['search']) : '';
$min_price     = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$max_price     = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
$sort          = isset($_GET['sort']) ? $_GET['sort'] : 'new';
$cats = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();
$sql = "SELECT p.*, c.category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id_category 
        WHERE 1=1";
$params = [];

if ($cat_filter > 0) {
    $sql .= " AND p.category_id = ?";
    $params[] = $cat_filter;
}

if (!empty($search_query)) {
    $sql .= " AND p.name LIKE ?";
    $params[] = "%$search_query%";
}

if ($min_price !== null) {
    $sql .= " AND p.price >= ?";
    $params[] = $min_price;
}

if ($max_price !== null) {
    $sql .= " AND p.price <= ?";
    $params[] = $max_price;
}
switch ($sort) {
    case 'price_asc':  $sql .= " ORDER BY p.price ASC"; break;
    case 'price_desc': $sql .= " ORDER BY p.price DESC"; break;
    case 'name_asc':   $sql .= " ORDER BY p.name ASC"; break;
    default:           $sql .= " ORDER BY p.id_products DESC"; break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ООО Кверт — Каталог техники</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .filter-section { margin-bottom: 20px; }
        .filter-section h3 { font-size: 16px; margin-bottom: 10px; color: #444; }
        .price-inputs { display: flex; gap: 5px; }
        .price-inputs input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .filter-btn { width: 100%; background: #ff6600; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .filter-btn:hover { background: #e65c00; }
        .reset-link { display: block; text-align: center; margin-top: 10px; font-size: 13px; color: #888; text-decoration: none; }
    </style>
</head>
<body style="display: block;">

<header style="background: #fff; padding: 15px 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100;">
    <a href="index.php" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
        <img src="assets/img/logo.png" alt="Лого" style="height: 35px; width: auto;" onerror="this.style.display='none'">
        <h2 style="margin: 0; color: #ff6600;">ООО «Кверт»</h2>
    </a>
    <form action="index.php" method="GET" class="search-form">
        <input type="text" name="search" class="search-input" placeholder="Поиск техники..." value="<?= htmlspecialchars($search_query) ?>">
        <?php if($cat_filter > 0): ?><input type="hidden" name="cat" value="<?= $cat_filter ?>"><?php endif; ?>
        <button type="submit" class="search-btn">🔍</button>
    </form>

    <nav style="display: flex; align-items: center; gap: 20px;">
        <a href="cart.php" style="text-decoration: none; color: #333;">
            🛒 Корзина <?php if($cart_count > 0): ?><span class="cart-badge"><?= $cart_count ?></span><?php endif; ?>
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php" style="text-decoration: none; color: #333;">Профиль</a>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="admin.php" style="color: #ffc107; font-weight: bold; text-decoration: none;">Админка</a>
            <?php endif; ?>
            <a href="logout.php" style="color: #dc3545; text-decoration: none;">Выход</a>
        <?php else: ?>
            <a href="login.php" style="text-decoration: none; color: #ff6600;">Вход</a>
            <a href="register.php" style="background: #ff6600; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none;">Регистрация</a>
        <?php endif; ?>
    </nav>
</header>

<div class="main-layout">
    <aside class="sidebar">
        <form action="index.php" method="GET">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search_query) ?>">

            <div class="filter-section">
                <h3>Категории</h3>
                <a href="index.php?search=<?= urlencode($search_query) ?>" class="cat-link <?= ($cat_filter == 0) ? 'active' : '' ?>">Все товары</a>
                <?php foreach($cats as $c): ?>
                    <a href="?cat=<?= $c['id_category'] ?>&search=<?= urlencode($search_query) ?>&sort=<?= $sort ?>&min_price=<?= $min_price ?>&max_price=<?= $max_price ?>" 
                       class="cat-link <?= ($cat_filter == $c['id_category']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($c['category_name']) ?>
                    </a>
                <?php endforeach; ?>
                <input type="hidden" name="cat" value="<?= $cat_filter ?>">
            </div>

            <hr style="border:0; border-top: 1px solid #eee; margin: 20px 0;">

            <div class="filter-section">
                <h3>Цена (₽)</h3>
                <div class="price-inputs">
                    <input type="number" name="min_price" placeholder="От" value="<?= $min_price ?>">
                    <input type="number" name="max_price" placeholder="До" value="<?= $max_price ?>">
                </div>
            </div>

            <div class="filter-section">
                <h3>Сортировка</h3>
                <select name="sort" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="new" <?= $sort == 'new' ? 'selected' : '' ?>>Сначала новые</option>
                    <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>Дешевле</option>
                    <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>Дороже</option>
                    <option value="name_asc" <?= $sort == 'name_asc' ? 'selected' : '' ?>>По названию (А-Я)</option>
                </select>
            </div>

            <button type="submit" class="filter-btn">Применить</button>
            <a href="index.php" class="reset-link">Сбросить все фильтры</a>
        </form>
    </aside>

    <main style="flex: 1;">
        <?php if (!empty($search_query) || $cat_filter > 0 || $min_price || $max_price): ?>
            <p style="margin-bottom: 20px;">
                Найдено товаров: <b><?= count($products) ?></b>
                <?php if (!empty($search_query)): ?> для "<?= htmlspecialchars($search_query) ?>"<?php endif; ?>
            </p>
        <?php endif; ?>

        <div class="product-grid">
            <?php if(empty($products)): ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: white; border-radius: 12px; border: 1px solid #eee;">
                    <h3>Ничего не найдено</h3>
                    <p>Попробуйте изменить параметры фильтрации</p>
                </div>
            <?php endif; ?>

            <?php foreach ($products as $p): ?>
            <div class="product-card">
                <div class="product-icon">
                    <img src="assets/img/<?= htmlspecialchars($p['image']) ?>" 
                         alt="<?= htmlspecialchars($p['name']) ?>" 
                         style="max-width: 100%; max-height: 140px; object-fit: contain;"
                         onerror="this.src='https://via.placeholder.com'">
                </div>
                <div class="category-badge"><?= htmlspecialchars($p['category_name'] ?? 'Общее') ?></div>
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <div class="product-desc"><?= mb_strimwidth(htmlspecialchars($p['description']), 0, 80, "...") ?></div>
                <div class="product-price"><?= number_format($p['price'], 0, '.', ' ') ?> ₽</div>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $p['id_products'] ?>">
                    <button type="submit" class="btn-buy">В корзину</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<footer style="text-align: center; margin-top: 50px; padding: 30px; color: #888; font-size: 13px;">
    &copy; <?= date('Y') ?> Информационная система ООО «Кверт»
</footer>

</body>
</html>
