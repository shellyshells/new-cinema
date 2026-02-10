<?php
session_start();

spl_autoload_register(function (string $class) {
    $paths = [
        __DIR__ . '/app/models/' . $class . '.php',
        __DIR__ . '/app/controllers/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) { require_once $path; return; }
    }
});

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: index.php?action=login');
        exit;
    }
}

function requireAdmin(): void {
    if (!isAdmin()) {
        header('Location: index.php');
        exit;
    }
}

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'login':
        (new AuthController())->showLogin();
        break;
    case 'login_post':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->showRegister();
        break;
    case 'register_post':
        (new AuthController())->register();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    default:
        // Redirect to login for now
        header('Location: index.php?action=login');
        break;
}
