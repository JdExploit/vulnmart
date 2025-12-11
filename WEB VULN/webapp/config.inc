<?php
// ¡VULNERABILIDAD: Credenciales en texto plano!
define('DB_HOST', 'database');
define('DB_USER', 'root');
define('DB_PASS', 'toor123!');  // ¡CONTRASEÑA DÉBIL!
define('DB_NAME', 'vulnmart');

// Configuración de sesión vulnerable
ini_set('session.serialize_handler', 'php'); // ¡VULNERABLE A DESERIALIZACIÓN!
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
    return $result->fetch_assoc();
}

// Función vulnerable para subir archivos
function uploadImage($file) {
    // ¡VULNERABILIDAD: Validación de tipo MIME insuficiente!
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    
    // Verificación básica (fácil de eludir)
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file;
        }
    }
    return false;
}
?>