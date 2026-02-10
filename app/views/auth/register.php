<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — Absolute Cinema</title>
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
            <a href="index.php?action=login" class="btn btn-ghost btn-s">Connexion</a>
        </div>
    </div>
</nav>


<div class="login-wrap">
    <div class="login-box">

        <div class="login-header">
            <img src="public/img/absolutecinema.png" alt="Logo">
            <h2>Créer un compte</h2>
            <p>Rejoignez Absolute Cinema en quelques secondes</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="msg msg-err">⚠ <?= h($error) ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="post" action="index.php?action=register_post">

                <div class="field">
                    <label for="username">Nom d'utilisateur</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        autocomplete="username"
                        value="<?= h($_POST['username'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        autocomplete="email"
                        value="<?= h($_POST['email'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label for="password">Mot de passe <span style="color:var(--ink-ghost)">(min. 6 caractères)</span></label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="6"
                        autocomplete="new-password"
                    >
                </div>

                <div class="field">
                    <label for="confirm">Confirmer le mot de passe</label>
                    <input
                        type="password"
                        id="confirm"
                        name="confirm"
                        required
                        autocomplete="new-password"
                    >
                </div>

                <button type="submit" class="btn btn-main btn-full">Créer mon compte</button>
            </form>
        </div>

        <p style="text-align:center;margin-top:1.25rem;font-size:0.9rem;color:var(--ink-faded)">
            Déjà un compte ?
            <a href="index.php?action=login">Se connecter</a>
        </p>

    </div>
</div>

<script src="public/js/main.js"></script>
</body>
</html>
