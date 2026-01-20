<?php
require 'db.php';
require 'header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT admin_id, username, password_hash FROM admin_user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<h2>Admin Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post" action="login.php">
    <label>Username:
        <input type="text" name="username" required>
    </label><br><br>
    <label>Password:
        <input type="password" name="password" required>
    </label><br><br>
    <button type="submit">Login</button>
</form>

<?php require 'footer.php'; ?>

