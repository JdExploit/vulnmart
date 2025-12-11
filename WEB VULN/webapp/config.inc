<?php
// ¡VULNERABILIDAD: Credenciales en texto plano!
define('DB_HOST', getenv('MYSQL_HOST') ?: 'database');
define('DB_USER', getenv('MYSQL_USER') ?: 'root');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: 'toor123!');  // ¡CONTRASEÑA DÉBIL!
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'vulnmart');

// Configuración de sesión vulnerable
ini_set('session.serialize_handler', 'php'); // ¡VULNERABLE A DESERIALIZACIÓN!
ini_set('session.cookie_httponly', false); // ¡Permite acceso JavaScript!
session_start();

// Conexión MySQL vulnerable
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Función vulnerable para incluir archivos
function loadLanguage($lang) {
    // ¡VULNERABILIDAD: LFI - Sin validación de entrada!
    $file = 'languages/' . $lang . '.inc';
    if (file_exists($file)) {
        include($file);
    } else {
        include('languages/en.inc');
    }
}

// Función vulnerable de autenticación
function authenticate($username, $password) {
    global $mysqli;
    // ¡VULNERABILIDAD: SQL Injection - Concatenación directa!
    $query = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";
    $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

// Función vulnerable para subir archivos
function uploadImage($file) {
    // ¡VULNERABILIDAD: Validación de tipo MIME insuficiente!
    $target_dir = "uploads/";
    
    // Generar nombre único
    $filename = uniqid() . '_' . basename($file["name"]);
    $target_file = $target_dir . $filename;
    
    // Verificación básica (fácil de eludir)
    $check = @getimagesize($file["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file;
        }
    }
    return false;
}

// Función para logging vulnerable
function logAction($user_id, $action) {
    global $mysqli;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $query = "INSERT INTO logs (user_id, action, ip_address) VALUES ('$user_id', '$action', '$ip')";
    $mysqli->query($query);
}

// Función vulnerable de búsqueda
function searchProducts($term) {
    global $mysqli;
    // ¡VULNERABILIDAD: SQL Injection!
    $query = "SELECT * FROM products WHERE name LIKE '%$term%' OR description LIKE '%$term%'";
    return $mysqli->query($query);
}
?>
