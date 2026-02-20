<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showLogin(): void {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        $error = null;

        $user = $this->userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();

            if ($remember) {
                setcookie('remember_email', $email, time() + 60 * 60 * 24 * 30, '/', '', false, true);
            }
            header('Location: index.php');
        } else {
            $error = "Email ou mot de passe incorrect.";
            require __DIR__ . '/../views/auth/login.php';
        }
    }

    public function showRegister(): void {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function register(): void {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        $error = null;

        if (!$username || !$email || !$password) {
            $error = "Tous les champs sont obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email invalide.";
        } elseif (strlen($password) < 6) {
            $error = "Le mot de passe doit faire au moins 6 caractères.";
        } elseif ($password !== $confirm) {
            $error = "Les mots de passe ne correspondent pas.";
        } elseif ($this->userModel->emailExists($email)) {
            $error = "Cet email est déjà utilisé.";
        } elseif ($this->userModel->usernameExists($username)) {
            $error = "Ce nom d'utilisateur est déjà pris.";
        }

        if ($error) {
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        $this->userModel->create($username, $email, $password);
        $_SESSION['success'] = "Compte créé ! Vous pouvez vous connecter.";
        header('Location: index.php?action=login');
    }

    public function logout(): void {
        session_destroy();
        setcookie('remember_email', '', time() - 3600, '/');
        header('Location: index.php?action=login');
    }

    public function showProfile(): void {
        requireLogin();
        $user = $this->userModel->findById($_SESSION['user_id']);
        require __DIR__ . '/../views/auth/profile.php';
    }

    public function updateProfile(): void {
        requireLogin();
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $error = null;
        $success = null;

        if (!$username || !$email) {
            $error = "Nom et email obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email invalide.";
        } else {
            $this->userModel->update($_SESSION['user_id'], $username, $email, $password ?: null);
            $_SESSION['username'] = $username;
            $success = "Profil mis à jour.";
        }
        $user = $this->userModel->findById($_SESSION['user_id']);
        require __DIR__ . '/../views/auth/profile.php';
    }

    public function deleteAccount(): void {
        requireLogin();
        $this->userModel->delete($_SESSION['user_id']);
        session_destroy();
        header('Location: index.php?action=login');
    }
}
