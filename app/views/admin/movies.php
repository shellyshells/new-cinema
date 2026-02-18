<?php
$extraJs = 'public/js/admin.js';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-head">
    <h1>Administration — Films</h1>
</div>

<div class="admin-nav">
    <a href="index.php?action=admin_movies" class="on">🎬 Films</a>
    <a href="index.php?action=admin_rooms">🏠 Salles</a>
    <a href="index.php?action=admin_screenings">📅 Séances</a>
    <a href="index.php?action=admin_reservations">🎟️ Réservations</a>
    <a href="index.php?action=admin_users">👥 Utilisateurs</a>
</div>

<?php if (!empty($error)): ?>
    <div class="msg msg-err">⚠ <?= h($error) ?></div>
<?php endif; ?>

<div class="card" style="margin-bottom:1.5rem">
    <div style="font-size:0.95rem;font-weight:700;color:#fff;margin-bottom:1rem">Ajouter un film</div>
    <form method="post" action="index.php?action=create_movie">
        <div class="field-row">
            <div class="field">
                <label>Titre</label>
                <input type="text" name="title" required placeholder="Titre du film">
            </div>
            <div class="field">
                <label>Durée (min)</label>
                <input type="number" name="duration" required min="1" placeholder="120">
            </div>
        </div>
        <div class="field">
            <label>Description</label>
            <textarea name="description" rows="2" placeholder="Synopsis..."></textarea>
        </div>
        <button type="submit" class="btn btn-main">+ Ajouter</button>
    </form>
</div>

<div class="tbl-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Durée</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movies as $m): ?>
            <tr>
                <td style="color:var(--ink-ghost)"><?= $m['id'] ?></td>
                <td><strong><?= h($m['title']) ?></strong></td>
                <td style="color:var(--ink-faded);font-size:0.88rem;max-width:300px">
                    <?= h(mb_substr($m['description'], 0, 90)) ?>…
                </td>
                <td><?= $m['duration'] ?> min</td>
                <td>
                    <div style="display:flex;gap:0.5rem">
                        <button
                            class="btn btn-ghost btn-s js-edit-movie"
                            data-id="<?= $m['id'] ?>"
                            data-title="<?= h($m['title']) ?>"
                            data-desc="<?= h($m['description']) ?>"
                            data-dur="<?= $m['duration'] ?>"
                            data-modal-open="modal-edit-movie"
                        >Modifier</button>
                        <a
                            href="index.php?action=delete_movie&id=<?= $m['id'] ?>"
                            class="btn btn-red btn-s"
                            data-confirm="Supprimer le film « <?= h($m['title']) ?> » et toutes ses séances ?"
                        >Supprimer</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="overlay" id="modal-edit-movie">
    <div class="modal">
        <div class="modal-top">
            <h3>Modifier le film</h3>
            <button class="modal-x" data-modal-close="modal-edit-movie">✕</button>
        </div>
        <form method="post" action="index.php?action=update_movie">
            <input type="hidden" name="id" id="edit-movie-id">
            <div class="field">
                <label>Titre</label>
                <input type="text" name="title" id="edit-movie-title" required>
            </div>
            <div class="field">
                <label>Description</label>
                <textarea name="description" id="edit-movie-desc" rows="3"></textarea>
            </div>
            <div class="field">
                <label>Durée (min)</label>
                <input type="number" name="duration" id="edit-movie-dur" required min="1">
            </div>
            <div style="display:flex;gap:0.75rem">
                <button type="submit" class="btn btn-main">Enregistrer</button>
                <button type="button" class="btn btn-ghost" data-modal-close="modal-edit-movie">Annuler</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
