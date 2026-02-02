<?php

require_once __DIR__ . '/db.php';

function IsLoggedIn(): bool {
    return isset($_SESSION['user']);
}

function CurrentUser(): mixed {
    return $_SESSION['user'] ?? null;
}

function RequireLogin(): void {
    if (!IsLoggedIn()) {
        header(header: "Location: " . BASE_URL . "/auth/login.php");
        exit;
    }
}

function RequireAdmin(): void {
    RequireLogin();
    if (empty($_SESSION['user']['is_admin'])) {
        header(header: "Location: " . BASE_URL . "/index.php");
        exit;
    }
}

function FindUserByEmail(string $email): mixed {
    global $pdo;
    $stmt = $pdo->prepare(query: "SELECT * FROM users WHERE email = ?");
    $stmt->execute(params: [$email]);
    return $stmt->fetch();
}
