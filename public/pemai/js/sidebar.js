document.addEventListener("DOMContentLoaded", function () {
    // Cache elemen yang sering diakses
    const sidebar = document.getElementById("sidebar");
    const menuToggle = document.getElementById("menuToggle");
    const categoryLinks = document.querySelectorAll(".category-link");
    const userInfo = document.querySelector(".user-wrapper");
    const navItems = document.querySelectorAll(".nav-item a");
    const links = document.querySelectorAll('.link-icon');
    const navbar = document.querySelector('.navbar-top'); // Navbar
    const mainContent = document.querySelector('.main-content'); // Main content

    // Fungsi untuk menyimpan status sidebar di localStorage
    function saveSidebarState() {
        if (sidebar) {
            localStorage.setItem("sidebarActive", sidebar.classList.contains("active"));
        }
    }

    // Fungsi untuk memuat status sidebar dan kategori dari localStorage
    function loadState() {
        const isLoggedIn = document.body.classList.contains("logged-in");
        const isAdmin = document.body.classList.contains("admin");
        if (isLoggedIn && isAdmin) {
            const sidebarActive = JSON.parse(localStorage.getItem("sidebarActive"));
            if (sidebarActive && sidebar) {
                sidebar.classList.add("active");
                document.body.style.marginLeft = "200px";
                // Menambahkan animasi pada navbar dan main content saat sidebar dibuka
                navbar.classList.add("shifted");
                mainContent.classList.add("open");
            }
        } else {
            if (sidebar) {
                sidebar.classList.remove("active");
                document.body.style.marginLeft = "0";
                // Menghapus animasi pada navbar dan main content saat sidebar ditutup
                navbar.classList.remove("shifted");
                mainContent.classList.remove("open");
            }
        }
        const activeCategory = localStorage.getItem("activeCategory");
        if (activeCategory) {
            const activeLink = document.querySelector(`.category-link[data-category="${activeCategory}"]`);
            if (activeLink) {
                activeLink.classList.add("active");
                const dropdown = activeLink.nextElementSibling;
                if (dropdown && dropdown.classList.contains("dropdown")) {
                    dropdown.style.display = "block";
                }
            }
        }
    }

    // Toggle sidebar saat menu diklik
    if (menuToggle) {
        menuToggle.addEventListener("click", function () {
            if (sidebar) {
                sidebar.classList.toggle("active");
                document.body.style.marginLeft = sidebar.classList.contains("active") ? "200px" : "0";
                
                // Menambahkan atau menghapus kelas untuk navbar dan main content untuk animasi
                navbar.classList.toggle("shifted", sidebar.classList.contains("active"));
                mainContent.classList.toggle("open", sidebar.classList.contains("active"));
                
                saveSidebarState();
            }
        });
    }

    // Toggle dropdown kategori saat link diklik
    categoryLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const dropdown = this.nextElementSibling;
            const isActive = this.classList.toggle("active");
            dropdown.style.display = isActive ? "block" : "none";

            if (isActive) {
                localStorage.setItem("activeCategory", this.getAttribute("data-category"));
            } else {
                localStorage.removeItem("activeCategory");
            }

            categoryLinks.forEach((otherLink) => {
                if (otherLink !== this) {
                    otherLink.classList.remove("active");
                    const otherDropdown = otherLink.nextElementSibling;
                    if (otherDropdown) {
                        otherDropdown.style.display = "none";
                    }
                }
            });
        });
    });

    // Toggle dropdown user info saat diklik
    if (userInfo) {
        userInfo.addEventListener("click", function (e) {
            e.stopPropagation();
            this.classList.toggle("active");
            const userDropdown = document.getElementById("userDropdown");
            if (userDropdown) {
                userDropdown.style.display = this.classList.contains("active") ? "block" : "none";
            }
        });
    }

    document.addEventListener("click", function (e) {
        const userDropdown = document.getElementById("userDropdown");
        if (userInfo && !userInfo.contains(e.target) && userDropdown && !userDropdown.contains(e.target)) {
            userInfo.classList.remove("active");
            userDropdown.style.display = "none";
        }
    });

    // Tandai nav item yang aktif sesuai URL
    navItems.forEach((item) => {
        if (item.href === window.location.href) {
            item.classList.add("active");
        } else {
            item.classList.remove("active");
        }

        item.addEventListener("click", () => {
            localStorage.removeItem("activeCategory");
        });
    });

    // Buka sidebar saat kursor diarahkan ke area hover
    document.querySelectorAll('.nav-item a, .nav-dropdown-item a, .category-link').forEach((link) => {
        link.addEventListener('mouseenter', () => {
            if (sidebar) {
                sidebar.classList.add("active");
                document.body.style.marginLeft = "200px";
                // Menambahkan animasi pada navbar dan main content saat sidebar dibuka
                navbar.classList.add("shifted");
                mainContent.classList.add("open");
            }
        });

        link.addEventListener('mouseleave', (e) => {
            if (!e.relatedTarget || !e.relatedTarget.closest('#sidebar')) {
                if (sidebar) {
                    sidebar.classList.remove("active");
                    document.body.style.marginLeft = "0";
                    // Menghapus animasi pada navbar dan main content saat sidebar ditutup
                    navbar.classList.remove("shifted");
                    mainContent.classList.remove("open");
                }
            }
        });
    });

    // Inisialisasi status saat halaman dimuat
    loadState();
});
