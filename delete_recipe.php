<?php
require 'db.php';
require 'header.php';

if (empty($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$recipeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($recipeId > 0) {
    $stmt = $pdo->prepare("DELETE FROM ingredient WHERE recipe_id = ?");
    $stmt->execute([$recipeId]);

    $stmt = $pdo->prepare("DELETE FROM recipe WHERE recipe_id = ?");
    $stmt->execute([$recipeId]);
}

header("Location: admin.php");
exit;

