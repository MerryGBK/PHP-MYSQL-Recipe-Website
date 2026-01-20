<?php
require 'db.php';
require 'header.php';

if (empty($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query(
    "SELECT r.recipe_id, r.title, c.category_name
     FROM recipe r
     JOIN category c ON r.category_id = c.category_id
     ORDER BY r.title"
);
$recipes = $stmt->fetchAll();
?>

<h2>Admin Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? ''); ?></p>

<p><a href="recipe_form.php">Add New Recipe</a></p>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($recipes as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r['title']); ?></td>
            <td><?php echo htmlspecialchars($r['category_name']); ?></td>
            <td>
                <a href="recipe_form.php?id=<?php echo $r['recipe_id']; ?>">Edit</a> |
                <a href="delete_recipe.php?id=<?php echo $r['recipe_id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this recipe?');">
                   Delete
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

    <?php if (empty($recipes)): ?>
        <tr><td colspan="3">No recipes yet.</td></tr>
    <?php endif; ?>
</table>

<?php require 'footer.php'; ?>

