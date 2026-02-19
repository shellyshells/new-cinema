document.addEventListener('DOMContentLoaded', function () {

    var seatInput    = document.getElementById('seats-input');
    var seatMax      = document.getElementById('seats-max');
    var seatFeedback = document.getElementById('seat-feedback');
    var submitBtn    = document.getElementById('reserve-submit');

    if (!seatInput || !seatMax) return;

    var max = parseInt(seatMax.value, 10);

    function validate() {
        var val = parseInt(seatInput.value, 10);
        var ok = !isNaN(val) && val >= 1 && val <= Math.min(max, 10);

        if (isNaN(val) || val < 1) {
            seatFeedback.textContent = 'Minimum 1 place.';
            seatFeedback.style.color = 'var(--bad)';
        } else if (val > 10) {
            seatFeedback.textContent = 'Maximum 10 places par réservation.';
            seatFeedback.style.color = 'var(--bad)';
        } else if (val > max) {
            seatFeedback.textContent = 'Seulement ' + max + ' places disponibles.';
            seatFeedback.style.color = 'var(--bad)';
        } else {
            seatFeedback.textContent = '✓ ' + val + ' place(s) sélectionnée(s)';
            seatFeedback.style.color = 'var(--good)';
        }

        if (submitBtn) submitBtn.disabled = !ok;
    }

    seatInput.addEventListener('input', validate);
    validate();
});
