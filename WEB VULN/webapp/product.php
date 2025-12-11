<?php
require_once 'config.inc';

// ¡VULNERABILIDAD: LFI - Parámetro 'lang' sin validar!
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
loadLanguage($lang);

// ¡VULNERABILIDAD: SQL Injection en búsqueda!
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $query = "SELECT * FROM products WHERE name LIKE '%" . $search . "%' OR description LIKE '%" . $search . "%'";
} else {
    $query = "SELECT * FROM products";
}

$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - VulnMart</title>
</head>
<body>
    <h1>Products</h1>
    
    <!-- Formulario de búsqueda vulnerable -->
    <form method="GET">
        <input type="text" name="search" placeholder="Search products...">
        <input type="submit" value="Search">
    </form>

    <!-- ¡VULNERABILIDAD: LFI en selector de idioma! -->
    <div>
        Language: 
        <a href="?lang=en">English</a> | 
        <a href="?lang=es">Español</a> |
        <a href="?lang=fr">Français</a>
        <!-- Exploit: ?lang=../../../../etc/passwd%00 -->
    </div>

    <div class="products">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="product">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p>Price: $<?= $row['price'] ?></p>
                <a href="product.php?page=details&id=<?= $row['id'] ?>">View Details</a>
            </div>
        <?php endwhile; ?>
    </div>

    <?php
    // ¡VULNERABILIDAD: LFI en página de detalles!
    if (isset($_GET['page']) && $_GET['page'] === 'details') {
        $page = isset($_GET['lang']) ? $_GET['lang'] : 'details';
        include('languages/' . $page . '.inc');
    }
    ?>
</body>
</html>