<div class="sidebar" id="sidebar">
    <div class="logo-sidebar">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" />
    </div>

    @if (Auth::check() && Auth::user()->role == 'admin')
        <div class="nav-item">
            <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line icon"></i> Dashboard
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
                <i class="fas fa-boxes icon"></i> Products
            </a>
        </div>
        <div class="nav-dropdown-item category-container">
            <a href="#" class="category-link {{ request()->is('categories/*') ? 'active' : '' }}" onclick="toggleCategoryDropdown(event)">
                <i class="fas fa-shapes icon"></i> Category
                <!-- Ikon dropdown, misalnya panah ke bawah -->
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <div class="dropdown" id="categoryDropdown" style="display: none;">
                <a href="{{ route('categories.index') }}" class="dropdown-item">
                    <i class="fas fa-tag"></i> Product Category
                </a>
                <a href="{{ route('brands.index') }}" class="dropdown-item">
                    <i class="fas fa-star"></i> Brand Category
                </a>
            </div>
        </div>
        <div class="nav-item">
            <a href="{{ route('vouchers.index') }}" class="{{ request()->is('vouchers') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt icon"></i> Voucher
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('orders.index') }}" class="{{ request()->is('orders') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart icon"></i> Orders
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('costumers.index') }}" class="{{ request()->is('customers') ? 'active' : '' }}">
                <i class="fas fa-users icon"></i> Account
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('informations.index') }}" class="{{ request()->is('settings') ? 'active' : '' }}">
                <i class="fas fa-cog icon"></i> Information Web
            </a>
        </div>
        <div class="logout-container">
            <a href="{{ route('logout') }}" class="logout-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    @endif
</div>

<!-- Script untuk toggle dropdown pada menu Category -->
<script>
    function toggleCategoryDropdown(event) {
        event.preventDefault(); // Menghentikan perilaku default link
        var dropdown = document.getElementById('categoryDropdown');
        var arrow = document.getElementById('dropdownArrow');

        // Toggle tampilan dropdown dengan animasi
        if (dropdown.style.display === "none" || dropdown.style.display === "") {
            dropdown.style.display = "flex";
            arrow.classList.add('rotate');
        } else {
            dropdown.style.display = "none";
            arrow.classList.remove('rotate');
        }
    }
</script>
