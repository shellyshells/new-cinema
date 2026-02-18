<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-head">
    <h1>Administration — Séances</h1>
</div>

<div class="admin-nav">
    <a href="index.php?action=admin_movies">🎬 Films</a>
    <a href="index.php?action=admin_rooms">🏠 Salles</a>
    <a href="index.php?action=admin_screenings" class="on">📅 Séances</a>
    <a href="index.php?action=admin_reservations">🎟️ Réservations</a>
    <a href="index.php?action=admin_users">👥 Utilisateurs</a>
</div>

<?php if (!empty($error)): ?>
    <div class="msg msg-err">⚠ <?= h($error) ?></div>
<?php endif; ?>

<?php if (empty($rooms)): ?>
    <div class="msg msg-err">⚠ Vous devez d'abord <a href="index.php?action=admin_rooms" style="color:var(--accent-bright)">créer des salles</a> avant de pouvoir ajouter des séances.</div>
<?php else: ?>
<div class="card" style="margin-bottom:1.5rem">
    <div style="font-size:0.95rem;font-weight:700;color:#fff;margin-bottom:1rem">Ajouter une séance</div>
    <form method="post" action="index.php?action=create_screening">
        <div class="field-row">
            <div class="field" style="flex:2">
                <label>Film</label>
                <select name="movie_id" required>
                    <?php foreach ($movies as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= h($m['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field" style="flex:2">
                <label>Salle</label>
                <select name="room_id" required>
                    <?php foreach ($rooms as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= h($r['name']) ?> (<?= $r['capacity'] ?> places)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Date</label>
                <input type="date" name="date" required min="<?= date('Y-m-d') ?>">
            </div>
            <div class="field">
                <label>Heure</label>
                <input type="time" name="time" required>
            </div>
        </div>
        <button type="submit" class="btn btn-main">+ Ajouter</button>
    </form>
</div>
<?php endif; ?>

<div class="tbl-wrap">
    <table>
        <thead>
            <tr>
                <th>Film</th>
                <th>Salle</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Disponibles</th>
                <th>Capacité</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($screenings as $s): ?>
            <tr>
                <td><strong><?= h($s['movie_title']) ?></strong></td>
                <td><span style="color:var(--accent-bright)"><?= h($s['room_name']) ?></span></td>
                <td><?= date('d/m/Y', strtotime($s['screening_date'])) ?></td>
                <td><?= date('H:i',   strtotime($s['screening_time'])) ?></td>
                <td>
                    <?php if ($s['available_seats'] == 0): ?>
                        <span class="tag tag-full">Complet</span>
                    <?php else: ?>
                        <span class="tag tag-open"><?= $s['available_seats'] ?></span>
                    <?php endif; ?>
                </td>
                <td style="color:var(--ink-faded)"><?= $s['total_seats'] ?></td>
                <td>
                    <a
                        href="index.php?action=delete_screening&id=<?= $s['id'] ?>"
                        class="btn btn-red btn-s"
                        data-confirm="Supprimer cette séance ? Les réservations associées seront supprimées."
                    >Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
