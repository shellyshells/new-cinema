<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-head">
    <h1>Mes réservations</h1>
    <p>Historique de toutes vos réservations</p>
</div>

<?php if (empty($reservations)): ?>
    <div class="empty">
        <div class="empty-icon">🎟️</div>
        <h3>Aucune réservation</h3>
        <p>Vous n'avez pas encore réservé de places. <a href="index.php">Voir les films →</a></p>
    </div>
<?php else: ?>
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
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
                    <td><?= h($r['movie_title']) ?></td>
                    <td><span style="color:var(--accent-bright)"><?= h($r['room_name']) ?></span></td>
                    <td><?= date('d/m/Y', strtotime($r['screening_date'])) ?></td>
                    <td><?= date('H:i',   strtotime($r['screening_time'])) ?></td>
                    <td><strong><?= (int)$r['seats'] ?></strong></td>
                    <td style="color:var(--ink-faded);font-size:0.85rem"><?= date('d/m/Y à H:i', strtotime($r['created_at'])) ?></td>
                    <td>
                        <a
                            href="index.php?action=cancel_reservation&id=<?= $r['id'] ?>"
                            class="btn btn-red btn-s"
                            data-confirm="Annuler cette réservation pour <?= h($r['movie_title']) ?> ?"
                        >Annuler</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
