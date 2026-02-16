<?php
$extraJs = 'public/js/reservation.js';
require __DIR__ . '/../layouts/header.php';
?>

<a href="index.php?action=movie&id=<?= $screening['movie_id'] ?>" class="btn btn-ghost btn-s">← Retour aux séances</a>

<div class="page-head" style="margin-top:1.5rem">
    <h1>Réserver des places</h1>
    <p>
        <strong><?= h($screening['movie_title']) ?></strong>
        — <?= date('d/m/Y', strtotime($screening['screening_date'])) ?>
        à <?= date('H:i', strtotime($screening['screening_time'])) ?>
        — <span style="color:var(--accent-bright)"><?= h($screening['room_name']) ?></span>
    </p>
</div>

<?php if (!empty($error)): ?>
    <div class="msg msg-err">⚠ <?= h($error) ?></div>
<?php endif; ?>

<div style="max-width:480px">
    <div class="card">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--line)">
            <div>
                <div style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--ink-faded);margin-bottom:0.2rem">Places disponibles</div>
                <div style="font-size:1.6rem;font-weight:800;color:var(--accent-bright)"><?= $screening['available_seats'] ?></div>
            </div>
            <div>
                <div style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--ink-faded);margin-bottom:0.2rem">Capacité totale</div>
                <div style="font-size:1.6rem;font-weight:800;color:var(--ink-ghost)"><?= $screening['total_seats'] ?></div>
            </div>
        </div>

        <form method="post" action="index.php?action=store_reservation">
            <input type="hidden" name="screening_id" value="<?= $screening['id'] ?>">
            <input type="hidden" id="seats-max" value="<?= $screening['available_seats'] ?>">

            <div class="field">
                <label for="seats-input">Nombre de places <span style="color:var(--ink-ghost)">(max 10)</span></label>
                <input
                    type="number"
                    id="seats-input"
                    name="seats"
                    min="1"
                    max="<?= min(10, $screening['available_seats']) ?>"
                    value="1"
                    required
                >
                <div id="seat-feedback" style="font-size:0.85rem;margin-top:0.4rem;color:var(--ink-faded)"></div>
            </div>

            <button type="submit" id="reserve-submit" class="btn btn-main btn-full">
                🎟️ Confirmer la réservation
            </button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
