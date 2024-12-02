@extends('layouts.index')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row wishlist-row">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold">Sort by</span>
                    <a href="{{ route('wishlist.index') }}" class="btn btn-light ms-2">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="wishlist-btn-group">
                    <a href="{{ route('wishlist.index', ['sort' => 'newest']) }}"
                        class="btn {{ request('sort') == 'newest' ? 'btn-dark' : 'btn-light' }}">Newest</a>
                    <a href="{{ route('wishlist.index', ['sort' => 'bestselling']) }}"
                        class="btn {{ request('sort') == 'bestselling' ? 'btn-dark' : 'btn-light' }}">Bestselling</a>
                    <a href="{{ route('wishlist.index', ['sort' => 'lowest_price']) }}"
                        class="btn {{ request('sort') == 'lowest_price' ? 'btn-dark' : 'btn-light' }}">Lowest Price</a>
                    <a href="{{ route('wishlist.index', ['sort' => 'highest_price']) }}"
                        class="btn {{ request('sort') == 'highest_price' ? 'btn-dark' : 'btn-light' }}">Highest Price</a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-3 wishlist-product-list">
                @forelse ($wishlists as $wishlist)
                    <div class="col" id="wishlist-item-{{ $wishlist->product->id }}">
                        <a href="{{ route('products-all.show', $wishlist->product->id) }}" class="card wishlist-card">
                            <img src="{{ asset('storage/' . $wishlist->product->image_path) }}"
                                alt="{{ $wishlist->product->name }}" class="wishlist-card-img-top">
                            <div class="wishlist-card-body text-center">
                                <h6 class="wishlist-card-title">{{ $wishlist->product->name }}</h6>
                                <p class="wishlist-card-price">
                                    Rp{{ number_format($wishlist->product->price, 0, ',', '.') }}</p>
                                <button type="button" class="btn p-0 wishlist-toggle-btn"
                                    data-product-id="{{ $wishlist->product->id }}">
                                    <i class="fas fa-heart {{ in_array($wishlist->product->id, $userWishlistIds) ? 'text-danger' : 'text-secondary' }}"
                                        style="font-size: 18px;"></i>
                                </button>
                            </div>
                        </a>
                    </div>
                @empty
                    <p>Your wishlist is empty.</p>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $wishlists->appends(['sort' => request('sort')])->links() }}
            </div>
        </div>
    </div>
@endsection

<script>
    $(document).ready(function() {
        $('.wishlist-toggle-btn').on('click', function(event) {
            event.preventDefault();
            event.stopPropagation();

            var productId = $(this).data('product-id');
            var button = $(this);
            var icon = button.find('i');

            $.ajax({
                url: '{{ route('wishlist.add') }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.status === 'added') {
                        icon.removeClass('text-secondary').addClass('text-danger');
                    } else if (response.status === 'removed') {
                        icon.removeClass('text-danger').addClass('text-secondary');
                        $('#wishlist-item-' + productId).remove();
                    }

                    $('#wishlist-count').text(response.wishlistCount);
                },
                error: function() {
                    alert('Terjadi kesalahan saat memperbarui wishlist.');
                }
            });
        });
    });
</script>

<style>
    .wishlist-card {
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
        position: relative;
    }

    .wishlist-card:hover {
        text-decoration: none;
        color: inherit;
    }

    .wishlist-card-img-top {
        height: 150px;
        object-fit: cover;
        width: 100%;
    }

    .wishlist-card-body {
        padding: 15px;
    }

    .wishlist-card-title {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: left;
    }

    .wishlist-card-price {
        font-size: 12px;
        font-weight: bold;
        color: #99bc85;
        margin-bottom: 8px;
        text-align: left;
    }

    .wishlist-toggle-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        z-index: 10;
    }

    .wishlist-btn-group {
        display: flex;
        gap: 10px;
    }

    .wishlist-btn-group .btn {
        font-size: 14px;
        font-weight: bold;
        margin-right: 1px;
        padding: 8px 12px;
        border-radius: 4px;
    }

    .wishlist-btn-group .btn-light {
        background-color: #f9f9f9;
        border: px solid #ddd;
        color: #4a7c5b;
    }

    .wishlist-btn-group .btn-light:hover {
        background-color: #99bc85bd;
        color: #fff;
    }

    .wishlist-btn-group .btn-dark {
        background-color: #99bc85;
        border: 1px solid #99bc85;
        color: #fff;
    }

    .wishlist-btn-group .btn-dark:hover {
        background-color: #99bc85bd;
        border-color: #99bc85bd;
        color: #fff;
    }
</style>
