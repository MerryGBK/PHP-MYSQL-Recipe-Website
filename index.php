<?php
require 'db.php';
require 'header.php';

// fetch categories for filter
$catStmt = $pdo->query("SELECT category_id, category_name FROM category ORDER BY category_name");
$categories = $catStmt->fetchAll();

$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

if ($categoryFilter > 0) {
    $stmt = $pdo->prepare(
        "SELECT r.recipe_id, r.title, c.category_name, r.prep_time
         FROM recipe r
         JOIN category c ON r.category_id = c.category_id
         WHERE r.category_id = ?
         ORDER BY r.title"
    );
    $stmt->execute([$categoryFilter]);
} else {
    $stmt = $pdo->query(
        "SELECT r.recipe_id, r.title, c.category_name, r.prep_time
         FROM recipe r
         JOIN category c ON r.category_id = c.category_id
         ORDER BY r.title"
    );
}

$recipes = $stmt->fetchAll();
?>

<h2>All Recipes</h2>

<form method="get" action="index.php">
    <label for="category">Filter by category:</label>
    <select name="category" id="category">
        <option value="0">-- All Categories --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['category_id']; ?>"
                <?php if ($categoryFilter == $cat['category_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($cat['category_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
</form>

<ul>
    <?php foreach ($recipes as $recipe): ?>
        <li>
            <a href="recipe.php?id=<?php echo $recipe['recipe_id']; ?>">
                <?php echo htmlspecialchars($recipe['title']); ?>
            </a>
            (<?php echo htmlspecialchars($recipe['category_name']); ?>,
             Prep: <?php echo (int)$recipe['prep_time']; ?> mins)
        </li>
    <?php endforeach; ?>

    <?php if (empty($recipes)): ?>
        <li>No recipes found.</li>
    <?php endif; ?>
</ul>

<?php require 'footer.php'; ?>

