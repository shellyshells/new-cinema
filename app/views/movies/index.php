<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-head">
    <h1>Films à l'affiche</h1>
    <p>Choisissez un film et réservez vos places en quelques clics</p>
</div>

<?php if (empty($movies)): ?>
    <div class="empty">
        <div class="empty-icon">🎬</div>
        <h3>Aucun film disponible</h3>
        <p>Revenez bientôt pour découvrir nos prochaines séances.</p>
    </div>
<?php else: ?>
    <div class="films">
        <?php foreach ($movies as $movie): ?>
        <div class="film">
            <div class="film-icon">🎞️</div>
            <div class="film-title"><?= h($movie['title']) ?></div>
            <div class="film-desc">
                <?= h(mb_substr($movie['description'], 0, 110)) ?>…
            </div>
            <div class="film-info">
                <span>⏱</span>
                <span><?= (int)$movie['duration'] ?> min</span>
            </div>
            <a href="index.php?action=movie&id=<?= $movie['id'] ?>" class="btn btn-main">
                Voir les séances →
            </a>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
