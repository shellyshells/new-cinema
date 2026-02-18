<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-head">
    <h1>Administration — Utilisateurs</h1>
</div>

<div class="admin-nav">
    <a href="index.php?action=admin_movies">🎬 Films</a>
    <a href="index.php?action=admin_rooms">🏠 Salles</a>
    <a href="index.php?action=admin_screenings">📅 Séances</a>
    <a href="index.php?action=admin_reservations">🎟️ Réservations</a>
    <a href="index.php?action=admin_users" class="on">👥 Utilisateurs</a>
</div>

<div class="tbl-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Inscrit le</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td style="color:var(--ink-ghost)"><?= $u['id'] ?></td>
                <td><strong><?= h($u['username']) ?></strong></td>
                <td style="color:var(--ink-faded)"><?= h($u['email']) ?></td>
                <td><span class="tag tag-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
                <td style="color:var(--ink-faded);font-size:0.85rem"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <a
                            href="index.php?action=admin_delete_user&id=<?= $u['id'] ?>"
                            class="btn btn-red btn-s"
                            data-confirm="Supprimer l'utilisateur « <?= h($u['username']) ?> » et toutes ses données ?"
                        >Supprimer</a>
                    <?php else: ?>
                        <span style="font-size:0.82rem;color:var(--ink-ghost)">Vous</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
