document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.th-filter-btn');
    const transactionCards = document.querySelectorAll('.th-transaction-card');

    function updateTrackingVisibility() {
        transactionCards.forEach(card => {
            const orderStatus = card.dataset.orderStatus;
            const btnTrack = card.querySelector('.btn-track');
            const resiInfo = card.querySelector('.resi-info');

            if (btnTrack) btnTrack.style.display = 'none';
            if (resiInfo) resiInfo.style.display = 'none';

            if (orderStatus === 'shipped' && btnTrack) {
                btnTrack.style.display = 'inline-block';
            }

            if ((orderStatus === 'shipped' || orderStatus === 'delivered') && resiInfo) {
                resiInfo.style.display = 'inline-block';
            }
        });
    }

    transactionCards.forEach(card => card.style.display = 'block');
    updateTrackingVisibility();

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const status = this.dataset.status;
            filterButtons.forEach(btn => btn.classList.remove('filter-active'));
            this.classList.add('filter-active');

            transactionCards.forEach(card => {
                const orderStatus = card.dataset.orderStatus;
                card.style.display = (status === 'all' || orderStatus === status) ?
                    'block' : 'none';
            });

            updateTrackingVisibility();
        });
    });
});
