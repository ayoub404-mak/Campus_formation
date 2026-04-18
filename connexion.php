<?php
// Database Connection File you need to change based on your sql DB pararametrer

require_once '/var/www/config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", 
        DB_USER, 
        DB_PASS
    );
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // In production, don't show detailed error to users
    error_log("Database Connection Error: " . $e->getMessage());
    die("Sorry, we are experiencing technical difficulties. Please try again later.");
}
?>