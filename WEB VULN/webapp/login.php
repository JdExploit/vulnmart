<?php
require_once 'config.inc';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // ¡VULNERABILIDAD: SQL Injection clásica!
    $user = authenticate($username, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        // ¡VULNERABILIDAD: Cookie serializada!
        setcookie('user_data', serialize($user), time() + 3600, '/');
        
        $success = "Welcome, " . $user['username'] . "!";
        
        // ¡VULNERABILIDAD: Redirección abierta!
        if (isset($_GET['redirect'])) {
            header('Location: ' . $_GET['redirect']);
            exit;
        }
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - VulnMart</title>
</head>
<body>
    <h1>Login</h1>
    
    <?php if ($error): ?>
        <div style="color: red;"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div style="color: green;"><?= $success ?></div>
    <?php endif; ?>
    
    <!-- ¡VULNERABILIDAD: Login sin protección CSRF! -->
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>
    
    <div style="margin-top: 20px;">
        <h3>Test Credentials:</h3>
        <p>admin / admin123</p>
        <p>alice / password123</p>
    </div>
    
    <div style="margin-top: 20px; background: #ffe6e6; padding: 10px;">
        <h4>SQL Injection Examples:</h4>
        <p>Username: <code>' OR '1'='1</code></p>
        <p>Username: <code>admin' -- </code></p>
        <p>Username: <code>' UNION SELECT 1,2,3,4,5 -- </code></p>
    </div>
</body>
</html>