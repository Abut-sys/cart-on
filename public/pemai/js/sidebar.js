function saveSidebarState() {
    const sidebar = document.getElementById("sidebar");
    localStorage.setItem("sidebarActive", sidebar.classList.contains("active"));
}

function clearCategoryState() {
    localStorage.removeItem("activeCategory");
}

function loadState() {
    const sidebarActive = JSON.parse(localStorage.getItem("sidebarActive"));
    if (sidebarActive) {
        document.getElementById("sidebar").classList.add("active");
        document.body.style.marginLeft = "200px";
    }

    const activeCategory = localStorage.getItem("activeCategory");
    if (activeCategory) {
        const activeLink = document.querySelector(
            `.category-link[data-category="${activeCategory}"]`
        );
        if (activeLink) {
            activeLink.classList.add("active");
            const dropdown = activeLink.nextElementSibling;
            if (dropdown && dropdown.classList.contains("dropdown")) {
                dropdown.style.display = "block";
            }
        }
    }
}

document.getElementById("menuToggle").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("active");
    document.body.style.marginLeft = sidebar.classList.contains("active")
        ? "200px"
        : "0";
    saveSidebarState();
});

const categoryLinks = document.querySelectorAll(".category-link");
categoryLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
        e.preventDefault();
        const dropdown = this.nextElementSibling;
        const isActive = this.classList.toggle("active");

        if (dropdown) {
            dropdown.style.display = isActive ? "block" : "none";
        }

        if (isActive) {
            localStorage.setItem(
                "activeCategory",
                this.getAttribute("data-category")
            );
        } else {
            clearCategoryState();
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

document.querySelector(".user-info").addEventListener("click", function (e) {
    e.stopPropagation();
    this.classList.toggle("active");
    const userDropdown = document.getElementById("userDropdown");
    if (userDropdown) {
        userDropdown.style.display = this.classList.contains("active")
            ? "block"
            : "none";
    }
});

document.addEventListener("click", function () {
    const userInfo = document.querySelector(".user-info");
    if (userInfo.classList.contains("active")) {
        userInfo.classList.remove("active");
        document.getElementById("userDropdown").style.display = "none";
    }
});

const navItems = document.querySelectorAll(".nav-item a");
navItems.forEach((item) => {
    if (item.href === window.location.href) {
        item.classList.add("active");
    } else {
        item.classList.remove("active");
    }

    item.addEventListener("click", () => {
        clearCategoryState();
    });
});

