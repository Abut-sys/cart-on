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

                fetch('/wishlist/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
        let url = pageUrl || '/wishlist';
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

    // Sort buttons event listeners
    document.querySelectorAll('.sort-btn-wishlist').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const sort = this.dataset.sort;
            fetchSortedWishlist(sort);
        });
    });

    // Reset button event listener
    document.getElementById('reset-sort')?.addEventListener('click', function(e) {
        e.preventDefault();
        fetchSortedWishlist();
    });

    // Initial setup
    attachWishlistEvents();
    attachPaginationEvents(initialSort);
});
