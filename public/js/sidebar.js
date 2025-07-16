class SidebarHandler {
    constructor() {
        this.initialize();
    }

    initialize() {
        if (document.querySelector('.sidebar')) {
            this.setupAdminStatus();
        }
    }

    static toggleCategoryDropdown(event) {
        event.preventDefault();
        const dropdown = document.getElementById('categoryDropdown');
        const arrow = document.getElementById('categoryArrow');

        const isVisible = dropdown.style.display === 'block';
        dropdown.style.display = isVisible ? 'none' : 'block';
        arrow.classList.toggle('rotate', !isVisible);
    }

    static toggleReportDropdown(event) {
        event.preventDefault();
        const dropdown = document.getElementById('reportDropdown');
        const arrow = document.getElementById('reportArrow');

        const isVisible = dropdown.style.display === 'flex';
        dropdown.style.display = isVisible ? 'none' : 'flex';
        arrow.classList.toggle('rotate', !isVisible);
    }

    setupAdminStatus() {
        if (this.isAdmin()) {
            sessionStorage.setItem("isNewLogin", "true");
            document.body.classList.add("logged-in", "admin");
        }
    }

    isAdmin() {
        // This will be set via blade template
        return window.isAdmin || false;
    }
}
