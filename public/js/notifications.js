class NotificationHandler {
    constructor() {
        this.filterButtons = document.querySelectorAll('.th-filter-btn-notif');
        this.markAllPageBtn = document.getElementById('markAllAsReadPageBtn');
        this.initialize();
    }

    initialize() {
        this.setupFilterButtons();
        this.setupMarkAllAsRead();
    }

    setupFilterButtons() {
        this.filterButtons.forEach(button => {
            button.addEventListener('click', () => this.handleFilterClick(button));
        });
    }

    handleFilterClick(button) {
        const status = button.dataset.status;
        const url = new URL(window.location);
        url.searchParams.set('status', status);
        url.searchParams.delete('page');
        window.location = url.toString();
    }

    setupMarkAllAsRead() {
        if (this.markAllPageBtn) {
            this.markAllPageBtn.addEventListener('click', () => this.handleMarkAllAsRead());
        }
    }

    async handleMarkAllAsRead() {
        this.markAllPageBtn.disabled = true;
        this.markAllPageBtn.textContent = 'Marking...';

        try {
            const res = await fetch('/notifications/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    notification_ids: 'all'
                })
            });

            if (!res.ok) throw new Error('Failed to mark all as read');
            window.location.reload();
        } catch (error) {
            console.error(error);
            this.markAllPageBtn.disabled = false;
            this.markAllPageBtn.textContent = 'Mark All as Read';
            alert('Failed to mark all as read.');
        }
    }
}
