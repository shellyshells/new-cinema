<?php require __DIR__ . '/../layouts/header.php'; ?>

<a href="index.php" class="btn btn-ghost btn-s">← Retour aux films</a>

<div class="page-head" style="margin-top:1.5rem">
    <h1><?= h($movie['title']) ?></h1>
    <p><?= h($movie['description']) ?></p>
    <p style="margin-top:0.5rem;color:var(--accent-bright);font-weight:600">⏱ <?= (int)$movie['duration'] ?> minutes</p>
</div>

<h2 style="font-size:1.2rem;font-weight:700;margin-bottom:1rem;color:#fff">Séances disponibles</h2>

<?php if (empty($screenings)): ?>
    <div class="empty">
        <div class="empty-icon">📅</div>
        <h3>Aucune séance programmée</h3>
        <p>Aucune séance n'est disponible pour ce film pour le moment.</p>
    </div>
<?php else: ?>
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Salle</th>
                    <th>Disponibilité</th>
                    <th>Places restantes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($screenings as $s):
                    $pct = $s['total_seats'] > 0
                        ? round(($s['total_seats'] - $s['available_seats']) / $s['total_seats'] * 100)
                        : 100;
                    $full = $s['available_seats'] == 0;
                ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($s['screening_date'])) ?></td>
                    <td><?= date('H:i',   strtotime($s['screening_time'])) ?></td>
                    <td><span style="color:var(--accent-bright)"><?= h($s['room_name']) ?></span></td>
                    <td>
                        <div class="seats-bar">
                            <span style="width:<?= $pct ?>%" data-pct="<?= $pct ?>"></span>
                        </div>
                    </td>
                    <td>
                        <?php if ($full): ?>
                            <span class="tag tag-full">Complet</span>
                        <?php else: ?>
                            <span class="tag tag-open"><?= $s['available_seats'] ?> / <?= $s['total_seats'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$full): ?>
                            <?php if (isLoggedIn()): ?>
                                <a href="index.php?action=reserve&screening_id=<?= $s['id'] ?>" class="btn btn-main btn-s">Réserver</a>
                            <?php else: ?>
                                <a href="index.php?action=login" class="btn btn-ghost btn-s">Se connecter</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color:var(--ink-ghost);font-size:0.85rem">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
