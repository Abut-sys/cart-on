@extends('layouts.index')

@section('title', 'All Product')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row product-user-view-row">
            <div class="col-md-10 col-lg-3">
                <div class="product-user-view-filter-section">
                    <h5>Filter</h5>
                    <form id="filter-form" method="GET" action="{{ route('products-all.index') }}">
                        <div class="mb-3">
                            <label class="product-user-view-form-label">Color</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($color as $item)
                                    <button type="button"
                                        class="btn btn-outline-secondary product-user-view-filter-btn {{ in_array($item, (array) request('color')) ? 'active' : '' }}"
                                        data-value="{{ $item }}" data-target="color">
                                        {{ ucfirst($item) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="product-user-view-form-label">Size</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($size as $item)
                                    <button type="button"
                                        class="btn btn-outline-secondary product-user-view-filter-btn {{ in_array($item, (array) request('size')) ? 'active' : '' }}"
                                        data-value="{{ $item }}" data-target="size">
                                        {{ strtoupper($item) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="product-user-view-form-label">Brand</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($brand as $cat)
                                    <button type="button"
                                        class="btn btn-outline-secondary product-user-view-filter-btn {{ in_array($cat, (array) request('brand')) ? 'active' : '' }}"
                                        data-value="{{ $cat }}" data-target="brand">
                                        {{ ucfirst($cat) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="product-user-view-form-label">Category</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($category as $cat)
                                    <button type="button"
                                        class="btn btn-outline-secondary product-user-view-filter-btn {{ in_array($cat, (array) request('category')) ? 'active' : '' }}"
                                        data-value="{{ $cat }}" data-target="category">
                                        {{ ucfirst($cat) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div id="hidden-inputs">
                            @foreach ((array) request('color') as $color)
                                <input type="hidden" name="color[]" value="{{ $color }}">
                            @endforeach
                            @foreach ((array) request('size') as $size)
                                <input type="hidden" name="size[]" value="{{ $size }}">
                            @endforeach
                            @foreach ((array) request('brand') as $brand)
                                <input type="hidden" name="brand[]" value="{{ $brand }}">
                            @endforeach
                            @foreach ((array) request('category') as $category)
                                <input type="hidden" name="category[]" value="{{ $category }}">
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8 col-lg-9">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Sort by
                        <a href="{{ route('products-all.index') }}" class="btn btn-light ms-3">
                            <i class="fa fa-times"></i>
                        </a>
                    </span>
                    <div class="product-user-view-btn-group">
                        <a href="{{ route('products-all.index', array_merge(request()->all(), ['sort' => 'newest'])) }}"
                            class="btn {{ request('sort') == 'newest' ? 'btn-dark' : 'btn-light' }}">Newest</a>
                        <a href="{{ route('products-all.index', array_merge(request()->all(), ['sort' => 'bestselling'])) }}"
                            class="btn {{ request('sort') == 'bestselling' ? 'btn-dark' : 'btn-light' }}">Bestselling</a>
                        <a href="{{ route('products-all.index', array_merge(request()->all(), ['sort' => 'lowest_price'])) }}"
                            class="btn {{ request('sort') == 'lowest_price' ? 'btn-dark' : 'btn-light' }}">Lowest Price</a>
                        <a href="{{ route('products-all.index', array_merge(request()->all(), ['sort' => 'highest_price'])) }}"
                            class="btn {{ request('sort') == 'highest_price' ? 'btn-dark' : 'btn-light' }}">Highest
                            Price</a>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                    @foreach ($products as $product)
                        <div class="col">
                            <a href="{{ route('products-all.show', $product->id) }}" class="card product-user-view-card">
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                    class="product-user-view-card-img-top">
                                <div class="product-user-view-card-body text-center">
                                    <h6 class="product-user-view-card-title">{{ $product->name }}</h6>
                                    <p class="product-user-view-card-price">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                    @if (auth()->check())
                                        <i class="fas fa-shopping-cart product-user-view-toggle-cart-btn {{ in_array($product->id, $userCartIds) ? 'text-success' : 'text-secondary' }}"
                                            data-product-id="{{ $product->id }}"></i>

                                        <i class="fas fa-heart product-user-view-toggle-wishlist-btn {{ in_array($product->id, $userWishlistIds) ? 'text-danger' : 'text-secondary' }}"
                                            data-product-id="{{ $product->id }}"></i>

                                        </button>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {{ $products->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.product-user-view-filter-btn');
        const hiddenInputsContainer = document.getElementById('hidden-inputs');
        const filterForm = document.getElementById('filter-form');

        $('.product-user-view-toggle-cart-btn').on('click', function(event) {
            event.preventDefault();

            var productId = $(this).data('product-id');
            var $this = $(this);

            $.ajax({
                url: '{{ route('cart.add') }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.status === 'added') {
                        $this.removeClass('text-secondary').addClass(
                            'text-success');
                    } else if (response.status === 'removed') {
                        $this.removeClass('text-success').addClass(
                            'text-secondary');
                    }

                    $('#cart-count').text(response
                        .cartCount);
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat memperbarui cart.');
                    console.error("AJAX error: " + status + ": " + error);
                }
            });
        });

        $('.product-user-view-toggle-wishlist-btn').on('click', function(event) {
            event.preventDefault();

            var productId = $(this).data('product-id');
            var $this = $(this);

            $.ajax({
                url: '{{ route('wishlist.add') }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.status === 'added') {
                        $this.removeClass('text-secondary').addClass(
                            'text-danger');
                    } else if (response.status === 'removed') {
                        $this.removeClass('text-danger').addClass(
                            'text-secondary');
                    }

                    $('#wishlist-count').text(response
                        .wishlistCount);
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat memperbarui wishlist.');
                    console.error("AJAX error: " + status + ": " + error);
                }
            });
        });

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const value = button.getAttribute('data-value');
                const target = button.getAttribute('data-target');
                const inputName = `${target}[]`;

                button.classList.toggle('active');

                if (button.classList.contains('active')) {
                    button.innerHTML = `<i class="fa fa-check"></i> ${value}`;
                } else {
                    button.textContent = value;
                }

                const existingInput = hiddenInputsContainer.querySelector(
                    `input[name="${inputName}"][value="${value}"]`
                );
                if (existingInput) {
                    existingInput.remove();
                } else {
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = inputName;
                    newInput.value = value;
                    hiddenInputsContainer.appendChild(newInput);
                }

                filterForm.submit();
            });
        });

        const hiddenInputs = hiddenInputsContainer.querySelectorAll('input');
        hiddenInputs.forEach(input => {
            const value = input.value;
            const target = input.name.replace('[]', '');
            const button = document.querySelector(
                `.product-user-view-filter-btn[data-value="${value}"][data-target="${target}"]`
            );
            if (button) {
                button.classList.add('active');
                button.innerHTML = `<i class="fa fa-check"></i> ${value}`;
            }
        });
    });
</script>

<style>
    .product-user-view-card {
        height: 100%;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-decoration: none;
        color: inherit;
    }

    .product-user-view-card:hover {
        text-decoration: none;
        color: inherit;
    }

    .product-user-view-card:active {
        color: inherit;
        text-decoration: none;
    }

    .product-user-view-card:focus {
        outline: none;
        color: inherit;
    }

    .product-user-view-card-img-top {
        height: 150px;
        object-fit: cover;
        width: 100%;
    }

    .product-user-view-card-body {
        padding: 15px;
    }

    .product-user-view-card-title {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: left;
    }

    .product-user-view-card-price {
        font-size: 14px;
        font-weight: bold;
        color: #99bc85;
        margin-bottom: 8px;
        text-align: left;
    }

    .product-user-view-filter-section h5 {
        font-weight: 750;
        margin-bottom: 15px;
    }

    .product-user-view-form-label {
        margin-bottom: 1px;
        margin-top: 10px;
    }

    .product-user-view-filter-btn {
        margin-top: 10px;
        color: #4a7c5b !important;
        border-color: #4a7c5b !important;
        background-color: #dddddd70 !important;
    }

    .product-user-view-filter-btn:hover {
        background-color: #99bc85bd !important;
        color: #fff !important;
        border-color: #99bc85bd !important;
        font-weight: bold;
    }

    .product-user-view-filter-btn.active {
        background-color: #99bc85 !important;
        color: #fff !important;
        border-color: #99bc85 !important;
        font-weight: bold;
    }

    .product-user-view-filter-btn.active i {
        margin-right: 5px;
        font-size: 14px;
        font-weight: bold;
    }

    .product-user-view-form-label {
        font-size: 14px;
        font-weight: bold;
    }

    .product-user-view-btn-group {
        display: flex;
        gap: 10px;
    }

    .product-user-view-btn-group .btn {
        font-size: 14px;
        font-weight: bold;
        margin-right: 1px;
        padding: 8px 12px;
        border-radius: 4px;
    }

    .product-user-view-btn-group .btn-light {
        background-color: #f9f9f9;
        border: px solid #ddd;
        color: #4a7c5b;
    }

    .product-user-view-btn-group .btn-light:hover {
        background-color: #99bc85bd;
        color: #fff;
    }

    .product-user-view-btn-group .btn-dark {
        background-color: #99bc85;
        border: 1px solid #99bc85;
        color: #fff;
    }

    .product-user-view-btn-group .btn-dark:hover {
        background-color: #99bc85bd;
        border-color: #99bc85bd;
        color: #fff;
    }

    .product-user-view-toggle-cart-btn {
        position: absolute;
        bottom: 6px;
        right: 30px;
        z-index: 10;
        font-size: 20px;
    }

    .product-user-view-toggle-wishlist-btn {
        position: absolute;
        bottom: 6px;
        right: 6px;
        z-index: 10;
        font-size: 20px;
    }


</style>
