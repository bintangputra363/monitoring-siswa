<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");

// Path ke file config.php
$file_path = __DIR__ . '/config.php';

if (file_exists($file_path)) {
    echo file_get_contents($file_path);
} else {
    http_response_code(404);
    echo "File tidak ditemukan.";
}
?>