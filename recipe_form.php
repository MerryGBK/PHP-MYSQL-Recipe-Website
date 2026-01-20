<?php
require 'db.php';
require 'header.php';

if (empty($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// fetch categories
$catStmt = $pdo->query("SELECT category_id, category_name FROM category ORDER BY category_name");
$categories = $catStmt->fetchAll();

$recipeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $recipeId > 0;

$title = $description = $instructions = '';
$prep_time = '';
$category_id = '';
$message = '';

if ($editing) {
    $stmt = $pdo->prepare("SELECT * FROM recipe WHERE recipe_id = ?");
    $stmt->execute([$recipeId]);
    $recipe = $stmt->fetch();
    if (!$recipe) {
        $message = "Recipe not found.";
        $editing = false;
    } else {
        $title       = $recipe['title'];
        $description = $recipe['description'];
        $instructions= $recipe['instructions'];
        $prep_time   = $recipe['prep_time'];
        $category_id = $recipe['category_id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $instructions= trim($_POST['instructions'] ?? '');
    $prep_time   = (int)($_POST['prep_time'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);

    if ($title === '' || $category_id === 0) {
        $message = "Title and category are required.";
    } else {
        if ($editing) {
            $stmt = $pdo->prepare(
                "UPDATE recipe
                 SET title = ?, description = ?, category_id = ?, prep_time = ?, instructions = ?
                 WHERE recipe_id = ?"
            );
            $stmt->execute([$title, $description, $category_id, $prep_time, $instructions, $recipeId]);
            $message = "Recipe updated successfully.";
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO recipe (title, description, category_id, prep_time, instructions)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$title, $description, $category_id, $prep_time, $instructions]);
            $recipeId = $pdo->lastInsertId();
            $editing = true;
            $message = "Recipe added successfully.";
        }
    }
}
?>

<h2><?php echo $editing ? 'Edit Recipe' : 'Add New Recipe'; ?></h2>

<?php if ($message): ?>
    <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="post" action="">
    <label>Title:<br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
    </label><br><br>

    <label>Category:<br>
        <select name="category_id" required>
            <option value="">-- Select category --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?php echo $c['category_id']; ?>"
                    <?php if ($category_id == $c['category_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($c['category_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>Preparation time (mins):<br>
        <input type="number" name="prep_time"
               value="<?php echo htmlspecialchars((string)$prep_time); ?>">
    </label><br><br>

    <label>Description:<br>
        <textarea name="description" rows="3" cols="50"><?php
            echo htmlspecialchars($description);
        ?></textarea>
    </label><br><br>

    <label>Instructions:<br>
        <textarea name="instructions" rows="6" cols="50"><?php
            echo htmlspecialchars($instructions);
        ?></textarea>
    </label><br><br>

    <button type="submit"><?php echo $editing ? 'Update' : 'Create'; ?> Recipe</button>
</form>

<p><a href="admin.php">Back to admin list</a></p>

<?php require 'footer.php'; ?>

