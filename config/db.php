<?php
/**
 * CueMaster Pro — Database Connection
 * Uses PDO with prepared statements throughout the project.
 * Default XAMPP credentials: user "root", empty password.
 * Change these 4 constants only if your local MySQL setup differs.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'cuemaster_pro');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('
        <div style="font-family: sans-serif; background:#0A0B0D; color:#fff; padding:40px;">
            <h2 style="color:#FF4D5E;">Database Connection Failed</h2>
            <p>Make sure XAMPP MySQL is running and the database "cuemaster_pro" has been imported.</p>
            <p style="color:#8B939E;">Details: ' . htmlspecialchars($e->getMessage()) . '</p>
        </div>
    ');
}
