document.addEventListener('DOMContentLoaded', function() {
    const adminToggle = document.getElementById('adminToggle');
    const userDropdown = document.getElementById('userDropdown');
    const dropdownArrow = document.getElementById('dropdownArrow');

    if (adminToggle && userDropdown && dropdownArrow) {
        adminToggle.addEventListener('click', function(e) {
            e.preventDefault();

            // Toggle dropdown visibility
            userDropdown.classList.toggle('show');

            // Rotate arrow
            if (userDropdown.classList.contains('show')) {
                dropdownArrow.style.transform = 'rotate(180deg)';
            } else {
                dropdownArrow.style.transform = 'rotate(0deg)';
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!adminToggle.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
                dropdownArrow.style.transform = 'rotate(0deg)';
            }
        });
    }
});
