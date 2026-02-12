</div>
</main>

<footer class="ft">
    &copy; <?= date('Y') ?> Absolute Cinema — Tous droits réservés
</footer>

<script src="public/js/main.js"></script>
<?php if (!empty($extraJs)): ?>
    <script src="<?= $extraJs ?>"></script>
<?php endif; ?>

</body>
</html>
