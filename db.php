<?php

require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        dsn: "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        username: DB_USER,
        password: DB_PASS,
        options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
