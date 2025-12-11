<?php
require_once 'config.inc';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VulnMart - Home</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f0f0f0; }
        header { background: #333; color: white; padding: 15px; border-radius: 5px; }
        nav a { color: white; margin: 0 15px; text-decoration: none; }
        .products { display: flex; gap: 20px; margin-top: 20px; }
        .product { background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .vuln-alert { background: #ffcccc; padding: 10px; border-left: 4px solid red; margin: 10px 0; }
    </style>
</head>
<body>
    <header>
        <h1>🛍️ VulnMart - Online Store</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="product.php">Products</a>
            <a href="login.php">Login</a>
            <a href="admin/dashboard.php">Admin Panel</a>
        </nav>
    </header>

    <div class="vuln-alert">
        <strong>⚠️ DEMO VULNERABLE APPLICATION:</strong> This is intentionally vulnerable for educational purposes only!
    </div>

    <main>
        <h2>Welcome to VulnMart!</h2>
        <p>Your one-stop shop for all things tech.</p>
        
        <div class="products">
            <div class="product">
                <h3>Laptop Gaming</h3>
                <p>$1200.00</p>
                <a href="product.php?id=1">View Details</a>
            </div>
            <div class="product">
                <h3>Smartphone Pro</h3>
                <p>$800.00</p>
                <a href="product.php?id=2">View Details</a>
            </div>
        </div>

        <!-- ¡VULNERABILIDAD: XSS en comentarios! -->
        <section>
            <h3>Customer Reviews</h3>
            <form method="POST">
                <textarea name="comment" placeholder="Leave a review..."></textarea><br>
                <input type="submit" value="Submit Review">
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
                // ¡VULNERABILIDAD: XSS - Sin sanitización!
                echo "<div class='review'>" . $_POST['comment'] . "</div>";
            }
            ?>
        </section>
    </main>
</body>
</html>