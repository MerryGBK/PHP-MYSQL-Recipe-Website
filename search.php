<?php
require 'db.php';
require 'header.php';

$query = '';
$results = [];
$error = '';

if (!empty($_GET['q'])) {
    $query = trim($_GET['q']);

    if (strlen($query) > 100) {
        $error = "Search term too long.";
    } else {
        $like = '%' . $query . '%';

        $stmt = $pdo->prepare(
            "SELECT r.recipe_id, r.title, c.category_name
             FROM recipe r
             JOIN category c ON r.category_id = c.category_id
             WHERE r.title LIKE ? OR r.description LIKE ?
             ORDER BY r.title"
        );
        $stmt->execute([$like, $like]);
        $results = $stmt->fetchAll();
    }
}
?>

<h2>Search Recipes</h2>

<form method="get" action="search.php">
    <label for="q">Keyword:</label>
    <input type="text" name="q" id="q"
           value="<?php echo htmlspecialchars($query); ?>">
    <button type="submit">Search</button>
</form>

<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if ($query !== ''): ?>
    <h3>Results for "<?php echo htmlspecialchars($query); ?>"</h3>
    <ul>
        <?php if ($results): ?>
            <?php foreach ($results as $row): ?>
                <li>
                    <a href="recipe.php?id=<?php echo $row['recipe_id']; ?>">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </a>
                    (<?php echo htmlspecialchars($row['category_name']); ?>)
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No matching recipes found.</li>
        <?php endif; ?>
    </ul>
<?php endif; ?>

<?php require 'footer.php'; ?>

