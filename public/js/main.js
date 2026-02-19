document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.msg[data-auto-dismiss]').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 500);
        }, 4000);
    });

    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(el.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-modal-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-modal-open');
            var modal = document.getElementById(id);
            if (modal) modal.classList.add('open');
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-modal-close');
            var modal = document.getElementById(id);
            if (modal) modal.classList.remove('open');
        });
    });

    document.querySelectorAll('.overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) overlay.classList.remove('open');
        });
    });

    document.querySelectorAll('.seats-fill').forEach(function (bar) {
        var pct = bar.getAttribute('data-pct') || '0';
        bar.style.width = pct + '%';
    });

    var currentAction = new URLSearchParams(window.location.search).get('action') || 'home';
    document.querySelectorAll('.nav-links a[data-action]').forEach(function (link) {
        if (link.getAttribute('data-action') === currentAction) {
            link.classList.add('on');
        }
    });

});
