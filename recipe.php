<?php
require 'db.php';
require 'header.php';

$recipeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT r.*, c.category_name
                       FROM recipe r
                       JOIN category c ON r.category_id = c.category_id
                       WHERE r.recipe_id = ?");
$stmt->execute([$recipeId]);
$recipe = $stmt->fetch();

if (!$recipe): ?>
    <h2>Recipe not found</h2>
<?php else: ?>

    <h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($recipe['category_name']); ?></p>
    <p><strong>Prep time:</strong> <?php echo (int)$recipe['prep_time']; ?> mins</p>
    <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>

    <h3>Ingredients</h3>
    <ul>
        <?php
        $ingStmt = $pdo->prepare("SELECT ingredient_name FROM ingredient WHERE recipe_id = ?");
        $ingStmt->execute([$recipeId]);
        $ingredients = $ingStmt->fetchAll();
        if ($ingredients):
            foreach ($ingredients as $ing):
                echo '<li>' . htmlspecialchars($ing['ingredient_name']) . '</li>';
            endforeach;
        else:
            echo '<li>No ingredients listed.</li>';
        endif;
        ?>
    </ul>

    <h3>Instructions</h3>
    <p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>

<?php endif; ?>

<?php require 'footer.php'; ?>

