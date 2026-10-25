<?php
require 'db.php';

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE products SET category_id = ?, title = ?, description = ?, price = ?, file_type = ? WHERE product_id = ?");
    $stmt->bind_param("issdsi", $_POST['category_id'], $_POST['title'], $_POST['description'], $_POST['price'], $_POST['file_type'], $_POST['product_id']);
    $stmt->execute();
    header("Location: products.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) { die("Product not found."); }

$categories = $conn->query("SELECT category_id, category_name FROM categories ORDER BY category_name");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit Product</h2>
    <form method="POST">
        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
        <select name="category_id" required>
            <?php while ($c = $categories->fetch_assoc()): ?>
                <option value="<?= $c['category_id'] ?>" <?= $c['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['category_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required>
        <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
        <input type="number" step="0.01" min="0" name="price" value="<?= $product['price'] ?>" required>
        <input type="text" name="file_type" value="<?= htmlspecialchars($product['file_type']) ?>">
        <button type="submit">Save Changes</button>
        <a href="products.php">Cancel</a>
    </form>
</body>
</html>