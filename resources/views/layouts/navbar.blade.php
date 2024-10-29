<nav class="navbar-top">
    <div class="menu-icon">
        <i class="fas fa-bars" id="menuToggle"></i>
    </div>

    <div class="search-bar">
        <input type="text" placeholder="Tap to search" class="search-input" />
        <i class="fas fa-search search-icon"></i>
    </div>

    <div class="user-info">
        <i class="fas fa-bell notification-icon"></i>
        <a href="#" class="username" id="adminToggle">Admin</a>
        <div class="user-dropdown" id="userDropdown">
            <a href="#" class="dropdown-item">Profile</a>
            <a href="#" class="dropdown-item">Log Out</a>
        </div>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <div class="logo">
        <img src="image/Oon.png" alt="Logo" />
    </div>
    <div class="nav-item">
        <a href="#" class="active"><i class="fas fa-chart-line icon"></i> Dashboard</a>
    </div>
    <div class="nav-item">
        <a href="#" id="products-link"><i class="fas fa-boxes icon"></i> Products</a>
    </div>
    <div class="nav-item">
        <a href="#" class="category-link"><i class="fas fa-shapes icon"></i>Category <i class="fa-solid fa-caret-down px-2"></i></a>
        <div class="dropdown"> 
            <a href="{{ route('categories.index') }}" class="dropdown-item">Product Category</a>
            <a href="#" class="dropdown-item">Brand Category</a>
        </div>
    </div>
    <div class="nav-item">
        <a href="#"><i class="fas fa-ticket-alt icon"></i> Voucher</a>
    </div>
    <div class="nav-item">
        <a href="#"><i class="fas fa-shopping-cart icon"></i> Orders</a>
    </div>
    <div class="nav-item">
        <a href="#"><i class="fas fa-users icon"></i> Customers</a>
    </div>
    <div class="nav-item">
        <a href="#"><i class="fas fa-truck icon"></i> Package Status</a>
    </div>
    <div class="nav-item">
        <a href="#"><i class="fas fa-cog icon"></i> Settings</a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuToggle = document.getElementById("menuToggle");
        const sidebar = document.getElementById("sidebar");
        const container = document.querySelector('.container');

        menuToggle.addEventListener("click", function() {
            sidebar.classList.toggle("active");
            if (container) container.classList.toggle("shrink");
            document.body.style.overflow = sidebar.classList.contains("active") ? "hidden" : "auto";
        });

        const navLinks = document.querySelectorAll('.sidebar .nav-item a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        const categoryLink = document.querySelector('.category-link');
        const dropdown = document.querySelector('.dropdown');

        categoryLink.addEventListener('click', function(event) {
            event.preventDefault();
            dropdown.classList.toggle('active');
        });

        const adminToggle = document.getElementById("adminToggle");
        const userDropdown = document.getElementById("userDropdown");

        adminToggle.addEventListener("click", function(event) {
            event.preventDefault();
            userDropdown.classList.toggle("active");
        });

        // Close dropdown if clicked outside
        document.addEventListener("click", function(event) {
            if (!adminToggle.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.remove("active");
            }
        });
    });
</script>
