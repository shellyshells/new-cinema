document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.js-edit-movie').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var modal = document.getElementById('modal-edit-movie');
            if (!modal) return;

            modal.querySelector('#edit-movie-id').value    = btn.getAttribute('data-id');
            modal.querySelector('#edit-movie-title').value = btn.getAttribute('data-title');
            modal.querySelector('#edit-movie-desc').value  = btn.getAttribute('data-desc');
            modal.querySelector('#edit-movie-dur').value   = btn.getAttribute('data-dur');

            modal.classList.add('open');
        });
    });
    var seatInput = document.getElementById('seats-input');
    var seatMax   = document.getElementById('seats-max');
    var seatFeedback = document.getElementById('seat-feedback');

    if (seatInput && seatMax && seatFeedback) {
        var max = parseInt(seatMax.value, 10);

        seatInput.addEventListener('input', function () {
            var val = parseInt(seatInput.value, 10);
            if (isNaN(val) || val < 1) {
                seatFeedback.textContent = 'Entrez au moins 1 place.';
                seatFeedback.style.color = 'var(--bad)';
            } else if (val > max) {
                seatFeedback.textContent = 'Seulement ' + max + ' places disponibles.';
                seatFeedback.style.color = 'var(--bad)';
            } else {
                seatFeedback.textContent = val + ' place(s) sélectionnée(s)';
                seatFeedback.style.color = 'var(--good)';
            }
        });
    }

});
