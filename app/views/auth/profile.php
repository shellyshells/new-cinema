<?php require __DIR__ . '/../layouts/header.php'; ?>

<div style="max-width:580px">

    <div class="page-head">
        <h1>Mon profil</h1>
        <p>Gérez vos informations personnelles</p>
    </div>

    <?php if (!empty($error)):   ?><div class="msg msg-err">⚠ <?= h($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="msg msg-ok" data-auto-dismiss>✓ <?= h($success) ?></div><?php endif; ?>

    <div class="card">
        <form method="post" action="index.php?action=update_profile">

            <div class="field">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required value="<?= h($user['username']) ?>">
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= h($user['email']) ?>">
            </div>

            <hr class="sep">

            <div class="field">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" minlength="6" placeholder="Laisser vide pour ne pas modifier">
            </div>

            <button type="submit" class="btn btn-main">Enregistrer les modifications</button>
        </form>
    </div>

    <div class="danger-box" style="margin-top:2rem;">
        <div>
            <h4>Supprimer mon compte</h4>
            <p>Cette action est irréversible. Toutes vos données seront supprimées.</p>
        </div>
        <form method="post" action="index.php?action=delete_account">
            <button
                type="submit"
                class="btn btn-red"
                data-confirm="Êtes-vous sûr de vouloir supprimer votre compte définitivement ?"
            >Supprimer</button>
        </form>
    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
