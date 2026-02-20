<?php
session_start();

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_destroy();
    header('Location: index.php?action=login&timeout=1');
    exit;
}
if (isset($_SESSION['user_id'])) {
    $_SESSION['last_activity'] = time();
}

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
    case 'profile':
        (new AuthController())->showProfile();
        break;
    case 'update_profile':
        (new AuthController())->updateProfile();
        break;
    case 'delete_account':
        (new AuthController())->deleteAccount();
        break;

    case 'movie':
        (new MovieController())->show();
        break;

    case 'reservations':
        (new ReservationController())->index();
        break;
    case 'reserve':
        (new ReservationController())->create();
        break;
    case 'store_reservation':
        (new ReservationController())->store();
        break;
    case 'cancel_reservation':
        (new ReservationController())->cancel();
        break;

    // Admin
    case 'admin_movies':
        (new AdminController())->movies();
        break;
    case 'create_movie':
        (new AdminController())->createMovie();
        break;
    case 'update_movie':
        (new AdminController())->updateMovie();
        break;
    case 'delete_movie':
        (new AdminController())->deleteMovie();
        break;
    case 'admin_screenings':
        (new AdminController())->screenings();
        break;
    case 'create_screening':
        (new AdminController())->createScreening();
        break;
    case 'delete_screening':
        (new AdminController())->deleteScreening();
        break;
    case 'admin_reservations':
        (new AdminController())->reservations();
        break;
    case 'admin_rooms':
        (new AdminController())->rooms();
        break;
    case 'create_room':
        (new AdminController())->createRoom();
        break;
    case 'update_room':
        (new AdminController())->updateRoom();
        break;
    case 'delete_room':
        (new AdminController())->deleteRoom();
        break;
    case 'admin_users':
        (new AdminController())->users();
        break;
    case 'admin_delete_user':
        (new AdminController())->deleteUser();
        break;

    default:
        (new MovieController())->index();
        break;
}
