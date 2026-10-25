<?php
require 'db.php';

$totalCategories = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
$totalProducts   = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];

// Products per category (shows the one-to-many relationship)
$perCategory = $conn->query("SELECT c.category_name, COUNT(p.product_id) AS total
                             FROM categories c
                             LEFT JOIN products p ON c.category_id = p.category_id
                             GROUP BY c.category_id, c.category_name
                             ORDER BY c.category_name");

// Latest products
$latest = $conn->query("SELECT p.title, p.price, c.category_name
                        FROM products p
                        JOIN categories c ON p.category_id = c.category_id
                        ORDER BY p.product_id DESC
                        LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Digital Products - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="products.php">Products</a>
    </nav>

    <div class="hero">
        <h1>Digital Products Store</h1>
        <p>Manage your categories and the digital products inside them.</p>
    </div>

    <div class="cards">
        <div class="card">
            <div class="number"><?= $totalCategories ?></div>
            <div class="label">Categories</div>
            <a class="card-btn" href="categories.php">Manage Categories</a>
        </div>
        <div class="card">
            <div class="number"><?= $totalProducts ?></div>
            <div class="label">Products</div>
            <a class="card-btn" href="products.php">Manage Products</a>
        </div>
    </div>

    <h2>Products per Category</h2>
    <table>
        <tr><th>Category</th><th>Number of Products</th></tr>
        <?php while ($row = $perCategory->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= $row['total'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Latest Products</h2>
    <table>
        <tr><th>Title</th><th>Category</th><th>Price</th></tr>
        <?php while ($row = $latest->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td>₱<?= number_format($row['price'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>