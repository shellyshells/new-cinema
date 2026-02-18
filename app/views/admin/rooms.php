<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-head">
    <h1>Administration — Salles</h1>
</div>

<div class="admin-nav">
    <a href="index.php?action=admin_movies">🎬 Films</a>
    <a href="index.php?action=admin_rooms" class="on">🏠 Salles</a>
    <a href="index.php?action=admin_screenings">📅 Séances</a>
    <a href="index.php?action=admin_reservations">🎟️ Réservations</a>
    <a href="index.php?action=admin_users">👥 Utilisateurs</a>
</div>

<?php if (!empty($error)): ?>
    <div class="msg msg-err">⚠ <?= h($error) ?></div>
<?php endif; ?>

<div class="card" style="margin-bottom:1.5rem">
    <div style="font-size:0.95rem;font-weight:700;color:#fff;margin-bottom:1rem">Ajouter une salle</div>
    <form method="post" action="index.php?action=create_room">
        <div class="field-row">
            <div class="field" style="flex:2">
                <label>Nom de la salle</label>
                <input type="text" name="name" placeholder="Ex: Salle 1 - Grande" required>
            </div>
            <div class="field">
                <label>Capacité (places)</label>
                <input type="number" name="capacity" min="1" value="100" required>
            </div>
        </div>
        <button type="submit" class="btn btn-main">+ Ajouter</button>
    </form>
</div>

<?php if (empty($rooms)): ?>
    <div class="empty">
        <div class="empty-icon">🏠</div>
        <h3>Aucune salle</h3>
        <p>Ajoutez des salles pour pouvoir créer des séances.</p>
    </div>
<?php else: ?>
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Capacité</th>
                    <th>Créée le</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                <tr>
                    <td style="color:var(--ink-ghost)"><?= $room['id'] ?></td>
                    <td><strong><?= h($room['name']) ?></strong></td>
                    <td>
                        <span class="tag tag-open"><?= (int)$room['capacity'] ?> places</span>
                    </td>
                    <td style="color:var(--ink-faded);font-size:0.85rem"><?= date('d/m/Y', strtotime($room['created_at'])) ?></td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-ghost btn-s"
                            onclick="editRoom(<?= $room['id'] ?>, '<?= h($room['name']) ?>', <?= (int)$room['capacity'] ?>)"
                        >Modifier</button>
                        <a
                            href="index.php?action=delete_room&id=<?= $room['id'] ?>"
                            class="btn btn-red btn-s"
                            data-confirm="Supprimer cette salle ? Les séances associées seront supprimées."
                        >Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div id="edit-room-modal" class="room-modal" style="display:none">
    <div class="room-modal-bg" onclick="closeEditModal()"></div>
    <div class="card room-modal-box">
        <div style="font-size:1rem;font-weight:700;color:#fff;margin-bottom:1rem">Modifier la salle</div>
        <form method="post" action="index.php?action=update_room">
            <input type="hidden" name="id" id="edit-room-id">
            <div class="field">
                <label>Nom de la salle</label>
                <input type="text" name="name" id="edit-room-name" required>
            </div>
            <div class="field">
                <label>Capacité (places)</label>
                <input type="number" name="capacity" id="edit-room-capacity" min="1" required>
            </div>
            <div style="display:flex;gap:0.5rem;margin-top:1rem">
                <button type="submit" class="btn btn-main">Enregistrer</button>
                <button type="button" class="btn btn-ghost" onclick="closeEditModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<style>
.room-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: center;
}
.room-modal-bg {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.7);
}
.room-modal-box {
    position: relative;
    z-index: 201;
    min-width: 320px;
    max-width: 90%;
}
</style>

<script>
function editRoom(id, name, capacity) {
    document.getElementById('edit-room-id').value = id;
    document.getElementById('edit-room-name').value = name;
    document.getElementById('edit-room-capacity').value = capacity;
    document.getElementById('edit-room-modal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('edit-room-modal').style.display = 'none';
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
