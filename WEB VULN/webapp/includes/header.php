<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VulnMart</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem; }
        nav { display: flex; gap: 1rem; margin-top: 1rem; }
        nav a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; }
        nav a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .alert { background: #fff3cd; border: 1px solid #ffeaa7; padding: 1rem; margin: 1rem 0; border-radius: 4px; }
        .vuln-badge { background: #dc3545; color: white; padding: 0.2rem 0.5rem; border-radius: 3px; font-size: 0.8rem; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>🛍️ VulnMart</h1>
            <p><span class="vuln-badge">VULNERABLE DEMO</span> Educational purposes only</p>
            <nav>
                <a href="/">Home</a>
                <a href="product.php">Products</a>
                <a href="login.php">Login</a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="admin/dashboard.php">Admin Panel</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="alert">
            ⚠️ <strong>Warning:</strong> This application contains intentional security vulnerabilities for educational purposes only. Do not deploy in production!
        </div>