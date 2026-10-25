<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // INSERT
    if (isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO products (category_id, title, description, price, file_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issds", $_POST['category_id'], $_POST['title'], $_POST['description'], $_POST['price'], $_POST['file_type']);
        $stmt->execute();
    }
    // DELETE
    if (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $_POST['product_id']);
        $stmt->execute();
    }
    header("Location: products.php");
    exit;
}

$categories = $conn->query("SELECT category_id, category_name FROM categories ORDER BY category_name");
$products = $conn->query("SELECT p.*, c.category_name
                          FROM products p
                          JOIN categories c ON p.category_id = c.category_id
                          ORDER BY p.product_id");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav><a href="index.php">Home</a><a href="categories.php">Categories</a><a href="products.php">Products</a></nav>

    <h2>Add Product</h2>
    <form method="POST">
        <select name="category_id" required>
            <option value="">-- Select category --</option>
            <?php while ($c = $categories->fetch_assoc()): ?>
                <option value="<?= $c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="title" placeholder="Product title" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" step="0.01" min="0" name="price" placeholder="Price" required>
        <input type="text" name="file_type" placeholder="File type (PDF, Canva, Notion...)">
        <button type="submit" name="add">Add Product</button>
    </form>

    <h2>All Products</h2>
    <table>
        <tr><th>ID</th><th>Category</th><th>Title</th><th>Description</th><th>Price</th><th>File Type</th><th>Actions</th></tr>
        <?php while ($row = $products->fetch_assoc()): ?>
        <tr>
            <td><?= $row['product_id'] ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td>₱<?= number_format($row['price'], 2) ?></td>
            <td><?= htmlspecialchars($row['file_type']) ?></td>
            <td>
                <a href="edit_product.php?id=<?= $row['product_id'] ?>">Edit</a>
                <form method="POST" style="display:inline"
                      onsubmit="return confirm('Delete this product?');">
                    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>