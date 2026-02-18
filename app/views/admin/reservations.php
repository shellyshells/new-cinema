<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-head">
    <h1>Administration — Réservations</h1>
</div>

<div class="admin-nav">
    <a href="index.php?action=admin_movies">🎬 Films</a>
    <a href="index.php?action=admin_rooms">🏠 Salles</a>
    <a href="index.php?action=admin_screenings">📅 Séances</a>
    <a href="index.php?action=admin_reservations" class="on">🎟️ Réservations</a>
    <a href="index.php?action=admin_users">👥 Utilisateurs</a>
</div>

<?php if (empty($reservations)): ?>
    <div class="empty">
        <div class="empty-icon">🎟️</div>
        <h3>Aucune réservation</h3>
        <p>Il n'y a pas encore de réservations enregistrées.</p>
    </div>
<?php else: ?>
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Film</th>
                    <th>Salle</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Places</th>
                    <th>Réservé le</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                <tr>
                    <td style="color:var(--ink-ghost)"><?= $r['id'] ?></td>
                    <td><?= h($r['username']) ?></td>
                    <td><strong><?= h($r['movie_title']) ?></strong></td>
                    <td><span style="color:var(--accent-bright)"><?= h($r['room_name']) ?></span></td>
                    <td><?= date('d/m/Y', strtotime($r['screening_date'])) ?></td>
                    <td><?= date('H:i',   strtotime($r['screening_time'])) ?></td>
                    <td><?= (int)$r['seats'] ?></td>
                    <td style="color:var(--ink-faded);font-size:0.85rem"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                    <td>
                        <a
                            href="index.php?action=cancel_reservation&id=<?= $r['id'] ?>"
                            class="btn btn-red btn-s"
                            data-confirm="Supprimer cette réservation ?"
                        >Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
