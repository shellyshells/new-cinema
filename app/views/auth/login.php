<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Absolute Cinema</title>
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
        <div class="nav-fill"></div>
        <div class="nav-right">
            <a href="index.php?action=register" class="btn btn-main btn-s">Inscription</a>
        </div>
    </div>
</nav>

<div class="login-wrap">
    <div class="login-box">

        <div class="login-header">
            <img src="public/img/absolutecinema.png" alt="Logo">
            <h2>Bon retour</h2>
            <p>Connectez-vous pour réserver vos places</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="msg msg-err">⚠ <?= h($error) ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="post" action="index.php?action=login_post">

                <div class="field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        autocomplete="email"
                        value="<?= h($_COOKIE['remember_email'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <div class="field">
                    <label class="check">
                        <input type="checkbox" name="remember" <?= !empty($_COOKIE['remember_email']) ? 'checked' : '' ?>>
                        Se souvenir de moi (30 jours)
                    </label>
                </div>

                <button type="submit" class="btn btn-main btn-full">Se connecter</button>
            </form>
        </div>

        <p style="text-align:center;margin-top:1.25rem;font-size:0.9rem;color:var(--ink-faded)">
            Pas encore de compte ?
            <a href="index.php?action=register">S'inscrire gratuitement</a>
        </p>

    </div>
</div>

<script src="public/js/main.js"></script>
</body>
</html>
