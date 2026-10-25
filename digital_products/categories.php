<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // INSERT
    if (isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $_POST['category_name'], $_POST['description']);
        $stmt->execute();
    }
    // DELETE
    if (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->bind_param("i", $_POST['category_id']);
        $stmt->execute();
    }
    header("Location: categories.php");
    exit;
}

$result = $conn->query("SELECT * FROM categories ORDER BY category_id");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Categories</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav><a href="index.php">Home</a><a href="categories.php">Categories</a><a href="products.php">Products</a></nav>

    <h2>Add Category</h2>
    <form method="POST">
        <input type="text" name="category_name" placeholder="Category name" required>
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit" name="add">Add Category</button>
    </form>

    <h2>All Categories</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['category_id'] ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td>
                <a href="edit_category.php?id=<?= $row['category_id'] ?>">Edit</a>
                <form method="POST" style="display:inline"
                      onsubmit="return confirm('Delete this category and ALL its products?');">
                    <input type="hidden" name="category_id" value="<?= $row['category_id'] ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>