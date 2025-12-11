<?php
require_once 'config.inc';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // ¡VULNERABILIDAD: SQL Injection clásica!
    $user = authenticate($username, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        // ¡VULNERABILIDAD: Cookie serializada!
        setcookie('user_data', serialize($user), time() + 3600, '/', '', false, false);
        
        // Log de login (vulnerable a log poisoning)
        logAction($user['id'], "Login successful");
        
        $success = "Welcome, " . htmlspecialchars($user['username']) . "!";
        
        // ¡VULNERABILIDAD: Redirección abierta!
        if (isset($_GET['redirect'])) {
            header('Location: ' . $_GET['redirect']);
            exit;
        }
    } else {
        $error = "Invalid credentials!";
        logAction(0, "Failed login attempt for: $username");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VulnMart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        button {
            width: 100%;
            padding: 0.75rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #5a67d8;
        }
        
        .message {
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }
        
        .success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }
        
        .test-credentials {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #e2e8f0;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .vuln-info {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #fff5f5;
            border-radius: 5px;
            font-size: 0.85rem;
            border-left: 4px solid #fc8181;
        }
        
        code {
            background: #f7fafc;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
        }
        
        .footer-links {
            text-align: center;
            margin-top: 1rem;
        }
        
        .footer-links a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🔐 Login to VulnMart</h2>
        
        <?php if ($error): ?>
            <div class="message error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="message success">
                <?= htmlspecialchars($success) ?>
                <p>Redirecting to <a href="index.php">home page</a>...</p>
                <script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 2000);
                </script>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="footer-links">
            <a href="index.php">← Back to Home</a>
        </div>
        
        <div class="test-credentials">
            <h4>Test Credentials:</h4>
            <p><strong>Admin:</strong> admin / admin123</p>
            <p><strong>User:</strong> alice / password123</p>
            <p><strong>User:</strong> bob / bob123</p>
        </div>
        
        <div class="vuln-info">
            <h4>🔓 SQL Injection Test Payloads:</h4>
            <p>Username: <code>' OR '1'='1</code> (no password needed)</p>
            <p>Username: <code>admin'--</code> (comment out password)</p>
            <p>Username: <code>' UNION SELECT 1,'admin','admin123','admin@test.com',1--</code></p>
            <p><small>Try in password field too: <code>' OR '1'='1'--</code></small></p>
        </div>
    </div>
</body>
</html>
