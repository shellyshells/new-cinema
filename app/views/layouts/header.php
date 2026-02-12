<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absolute Cinema</title>
    <link rel="icon" type="image/png" href="public/img/absolutecinema.png">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body>

<nav class="nav">
    <div class="wrap nav-inner">
        <a href="index.php" class="nav-brand">
            <img src="public/img/absolutecinema.png" alt="Logo">
            Absolute<span>Cinema</span>
        </a>

        <div class="nav-links">
            <a href="index.php" data-action="home">Films</a>
            <?php if (isLoggedIn()): ?>
                <a href="index.php?action=reservations" data-action="reservations">Mes réservations</a>
            <?php endif; ?>
            <?php if (isAdmin()): ?>
                <a href="index.php?action=admin_movies" data-action="admin_movies">Administration</a>
            <?php endif; ?>
        </div>

        <div class="nav-fill"></div>

        <div class="nav-right">
            <?php if (isLoggedIn()): ?>
                <a href="index.php?action=profile" class="nav-user">
                    👤 <?= h($_SESSION['username']) ?>
                    <?php if (isAdmin()): ?>
                        <span class="nav-admin">Admin</span>
                    <?php endif; ?>
                </a>
                <a href="index.php?action=logout" class="btn btn-ghost btn-s">Déconnexion</a>
            <?php else: ?>
                <a href="index.php?action=login" class="btn btn-ghost btn-s">Connexion</a>
                <a href="index.php?action=register" class="btn btn-main btn-s">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="page">
<div class="wrap">

<?php if (!empty($_SESSION['success'])): ?>
    <div class="msg msg-ok" data-auto-dismiss>
        ✓ <?= h($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
