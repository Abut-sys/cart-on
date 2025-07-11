@extends('layouts.index')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row wishlist-row">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold">Sort by</span>
                    <a href="#" id="reset-sort" class="btn btn-light ms-2">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="wishlist-btn-group">
                    <a href="#" data-sort="newest"
                        class="btn sort-btn-wishlist {{ request('sort') == 'newest' ? 'btn-dark' : 'btn-light' }}">Newest</a>
                    <a href="#" data-sort="bestselling"
                        class="btn sort-btn-wishlist {{ request('sort') == 'bestselling' ? 'btn-dark' : 'btn-light' }}">Bestselling</a>
                    <a href="#" data-sort="lowest_price"
                        class="btn sort-btn-wishlist {{ request('sort') == 'lowest_price' ? 'btn-dark' : 'btn-light' }}">Lowest
                        Price</a>
                    <a href="#" data-sort="highest_price"
                        class="btn sort-btn-wishlist {{ request('sort') == 'highest_price' ? 'btn-dark' : 'btn-light' }}">Highest
                        Price</a>
                </div>
            </div>

            <div id="wishlist-product-container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-3 wishlist-product-list">
                    @foreach ($wishlists as $wishlist)
                        <div class="col" id="wishlist-item-{{ $wishlist->product->id }}">
                            <a href="{{ route('products-all.show', $wishlist->product->id) }}" class="card wishlist-card">
                                <i class="fas fa-heart wishlist-toggle-btn
                    {{ in_array($wishlist->product->id, $userWishlistIds) ? 'text-danger' : 'text-secondary' }}"
                                    data-product-id="{{ $wishlist->product->id }}">
                                </i>

                                <img src="{{ asset('storage/' . $wishlist->product->images->first()->image_path) }}"
                                    alt="{{ $wishlist->product->name }}" class="wishlist-card-img-top">

                                <div class="wishlist-card-body text-center">
                                    <h6 class="wishlist-card-title">{{ $wishlist->product->name }}</h6>
                                    <p class="wishlist-card-price">
                                        Rp{{ number_format($wishlist->product->price, 0, ',', '.') }}</p>
                                    <div class="d-flex justify-content-between align-items-center px-2"
                                        style="font-size: 13px; color: gray;">
                                        <div class="d-flex align-items-center gap-1 text-warning">
                                            <i class="fas fa-star"></i>
                                            <span
                                                class="text-dark">{{ number_format($wishlist->product->rating ?? 4.5, 1) }}</span>
                                        </div>
                                        <span>{{ $wishlist->product->sales }}+ Sold</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $wishlists->appends(['sort' => request('sort')])->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wishlistContainer = document.querySelector('#wishlist-product-container');

            function updateBadge(count) {
                const navbarBadge = document.querySelector('#for-badge-count-wishlist');
                if (navbarBadge) {
                    navbarBadge.textContent = count;
                    navbarBadge.style.display = '';
                }
            }

            function attachWishlistEvents() {
                document.querySelectorAll('.wishlist-toggle-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const productId = this.dataset.productId;
                        const item = document.getElementById('wishlist-item-' + productId);
                        const icon = this;

                        fetch(`{{ route('wishlist.add') }}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    product_id: productId
                                })
                            })
                            .then(res => res.json())
                            .then(res => {
                                if (res.status === 'added') {
                                    icon.classList.remove('text-secondary');
                                    icon.classList.add('text-danger');
                                } else if (res.status === 'removed') {
                                    item?.remove();
                                }
                                updateBadge(res.wishlistCount);
                            })
                            .catch(err => console.error('Wishlist toggle error:', err));
                    });
                });
            }

            function updateSortButtons(activeSort) {
                document.querySelectorAll('.sort-btn-wishlist').forEach(btn => {
                    const isActive = btn.dataset.sort === activeSort;
                    btn.classList.toggle('btn-dark', isActive);
                    btn.classList.toggle('btn-light', !isActive);
                });
            }

            function attachPaginationEvents(sort) {
                document.querySelectorAll('#wishlist-product-container .pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const pageUrl = this.getAttribute('href');
                        fetchSortedWishlist(sort, pageUrl);
                    });
                });
            }

            function fetchSortedWishlist(sort = null, pageUrl = null) {
                let url = pageUrl || '{{ route('wishlist.index') }}';
                if (sort && !url.includes('?')) {
                    url += `?sort=${sort}`;
                } else if (sort && url.includes('?')) {
                    url += `&sort=${sort}`;
                }

                fetch(url)
                    .then(res => res.text())
                    .then(html => {
                        const temp = new DOMParser().parseFromString(html, 'text/html');
                        const newContent = temp.querySelector('#wishlist-product-container').innerHTML;
                        wishlistContainer.innerHTML = newContent;

                        attachWishlistEvents();
                        attachPaginationEvents(sort);
                        updateSortButtons(sort);
                    })
                    .catch(err => console.error('Sort fetch error:', err));
            }

            // sortBy
            document.querySelectorAll('.sort-btn-wishlist').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sort = this.dataset.sort;
                    fetchSortedWishlist(sort);
                });
            });

            // reset
            document.getElementById('reset-sort')?.addEventListener('click', function(e) {
                e.preventDefault();
                fetchSortedWishlist();
            });

            attachWishlistEvents();
            attachPaginationEvents('{{ request('sort') }}');
        });
    </script>
@endsection

