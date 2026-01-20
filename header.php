<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Manager</title>
</head>
<body>
<header>
    <h1>Recipe Manager</h1>
    <nav>
        <a href="index.php">Home</a> |
        <a href="search.php">Search</a>
        <?php if (!empty($_SESSION['admin_logged_in'])): ?>
            | <a href="admin.php">Admin</a>
            | <a href="logout.php">Logout</a>
        <?php else: ?>
            | <a href="login.php">Admin Login</a>
        <?php endif; ?>
    </nav>
    <hr>
</header>
<main>

