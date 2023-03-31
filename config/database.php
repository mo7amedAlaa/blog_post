<?php
// Database configuration
define('DB_HOST', 'localhost:4306');
define('DB_USER', 'root');
define('DB_PASSWORD','');
define('DB_NAME', 'logre');
try {
    // Create a PDO connection to the database
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e) {
    // If an exception is thrown, display an error message
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>