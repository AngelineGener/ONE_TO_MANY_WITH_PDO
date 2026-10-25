<?php
require 'db.php';

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?");
    $stmt->bind_param("ssi", $_POST['category_name'], $_POST['description'], $_POST['category_id']);
    $stmt->execute();
    header("Location: categories.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM categories WHERE category_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();
if (!$category) { die("Category not found."); }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit Category</h2>
    <form method="POST">
        <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">
        <input type="text" name="category_name" value="<?= htmlspecialchars($category['category_name']) ?>" required>
        <textarea name="description"><?= htmlspecialchars($category['description']) ?></textarea>
        <button type="submit">Save Changes</button>
        <a href="categories.php">Cancel</a>
    </form>
</body>
</html>