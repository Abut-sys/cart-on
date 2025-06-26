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
            <a href="#"
                class="category-link d-flex justify-content-between align-items-center {{ request()->is('categories/*') ? 'active' : '' }}"
                onclick="toggleCategoryDropdown(event)">
                <span><i class="fas fa-shapes me-2"></i> Category</span>
                <i id="categoryArrow" class="bi bi-caret-down-fill transition-transform"></i>
            </a>

            <div id="categoryDropdown" class="dropdown flex-column mt-1" style="display: none;">
                <a href="{{ route('categories.index') }}" class="dropdown-item ps-4">
                    <i class="fas fa-tag me-2"></i> Product Category
                </a>
                <a href="{{ route('brands.index') }}" class="dropdown-item ps-4">
                    <i class="fas fa-star me-2"></i> Brand Category
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
        <div class="nav-dropdown-item report-container">
            <a href="#"
                class="report-link d-flex justify-content-between align-items-center {{ request()->is('report/*') ? 'active' : '' }}"
                onclick="toggleReportDropdown(event)">
                <span><i class="fas fa-file-alt me-2"></i> Report</span>
                <i id="reportArrow" class="bi bi-caret-down-fill transition-transform"></i>
            </a>

            <div id="reportDropdown" class="dropdown flex-column mt-1" style="display: none;">
                <a href="{{ route('reports.products.index') }}" class="dropdown-item ps-4">
                    <i class="fas fa-box me-2"></i> Product Report
                </a>
                 <a href="{{ route('reports.categories.index') }}" class="dropdown-item ps-4">
                    <i class="bi bi-tags-fill me-2"></i> Category Report
                </a>
                <a href="{{ route('reports.brands.index') }}" class="dropdown-item ps-4">
                    <i class="bi bi-bag-fill me-2"></i> Brand Report
                </a>
                <a href="{{ route('reports.vouchers.index') }}" class="dropdown-item ps-4">
                    <i class="bi bi-percent me-2"></i> Voucher Report
                </a>
                <a href="{{ route('report.orders') }}" class="dropdown-item ps-4">
                    <i class="bi bi-cart-check-fill me-2"></i> Order Report
                </a>
            </div>
        </div>

        <div class="nav-item">
            <a href="{{ route('costumers.index') }}" class="{{ request()->is('customers') ? 'active' : '' }}">
                <i class="fas fa-users icon"></i> Account
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
        event.preventDefault();
        const dropdown = document.getElementById('categoryDropdown');
        const arrow = document.getElementById('categoryArrow');

        const isVisible = dropdown.style.display === 'flex';
        dropdown.style.display = isVisible ? 'none' : 'flex';
        arrow.classList.toggle('rotate', !isVisible);
    }

    function toggleReportDropdown(event) {
        event.preventDefault();
        const dropdown = document.getElementById('reportDropdown');
        const arrow = document.getElementById('reportArrow');

        const isVisible = dropdown.style.display === 'flex';
        dropdown.style.display = isVisible ? 'none' : 'flex';
        arrow.classList.toggle('rotate', !isVisible);
    }
</script>

<style>
    .transition-transform {
        transition: transform 0.3s ease;
    }

    .rotate {
        transform: rotate(180deg);
    }
</style>
