document.addEventListener("DOMContentLoaded", function () {
    // Cache elemen yang sering diakses
    const sidebar = document.getElementById("sidebar");
    const menuToggle = document.getElementById("menuToggle");
    const categoryLinks = document.querySelectorAll(".category-link");
    const userInfo = document.querySelector(".user-wrapper");
    const navItems = document.querySelectorAll(".nav-item a");
    const links = document.querySelectorAll('.link-icon');

    // Fungsi untuk menyimpan status sidebar di localStorage
    function saveSidebarState() {
        if (sidebar) {
            localStorage.setItem("sidebarActive", sidebar.classList.contains("active"));
        }
    }

    // Fungsi untuk membuka sidebar
    function openSidebar() {
        if (sidebar) {
            sidebar.classList.add("active");
            document.body.style.marginLeft = "200px";
            saveSidebarState();
        }
    }

    // Fungsi untuk menutup sidebar
    function closeSidebar() {
        if (sidebar) {
            sidebar.classList.remove("active");
            document.body.style.marginLeft = "0";
            saveSidebarState();
        }
    }

    // Fungsi untuk memuat status sidebar dan kategori dari localStorage
    function loadState() {
        // Cek status login dan role admin
        const isLoggedIn = document.body.classList.contains("logged-in");
        const isAdmin = document.body.classList.contains("admin");

        // Jika user adalah admin yang sudah login
        if (isLoggedIn && isAdmin) {
            // Cek apakah ini adalah session login baru
            const isNewLogin = sessionStorage.getItem("isNewLogin");
            const sidebarActive = JSON.parse(localStorage.getItem("sidebarActive"));

            // Jika login baru atau belum pernah set, buka sidebar otomatis
            if (isNewLogin === "true" || sidebarActive === null) {
                openSidebar();
                // Hapus flag login baru setelah sidebar dibuka
                sessionStorage.removeItem("isNewLogin");
            } else if (sidebarActive) {
                // Jika sebelumnya sidebar aktif, buka sidebar
                openSidebar();
            } else {
                // Jika sebelumnya sidebar tidak aktif, tutup sidebar
                closeSidebar();
            }
        } else {
            // Sembunyikan sidebar jika bukan admin atau belum login
            closeSidebar();
        }

        // Muat kategori yang aktif jika ada
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
                const isActive = sidebar.classList.contains("active");
                document.body.style.marginLeft = isActive ? "200px" : "0";
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

            // Simpan kategori aktif ke localStorage
            if (isActive) {
                localStorage.setItem("activeCategory", this.getAttribute("data-category"));
            } else {
                localStorage.removeItem("activeCategory");
            }

            // Tutup dropdown lain yang terbuka
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

    // Menutup dropdown user info jika klik di luar
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

        // Hapus kategori aktif saat menavigasi
        item.addEventListener("click", () => {
            localStorage.removeItem("activeCategory");
        });
    });

    // Inisialisasi status saat halaman dimuat
    loadState();
});
