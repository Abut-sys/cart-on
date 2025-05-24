@extends('layouts.index')

@section('title', 'Home')

@section('content')
    <div class="home-user-banner-container">
        <div class="home-user-banner-wrapper">
            <div class="home-user-banner">
                <img src="image/banner (2612 x 992 px).png" alt="Banner 1" class="home-user-banner-image">
                <img src="image/banner1.png" alt="Banner 2" class="home-user-banner-image">
                <img src="image/banner2.png" alt="Banner 3" class="home-user-banner-image">
            </div>
            <button class="home-user-banner-prev">&lt;</button>
            <button class="home-user-banner-next">&gt;</button>
        </div>
        <div class="home-user-ads">
            <div class="home-user-ad">
                <img src="image/iklan1.png" alt="Iklan 1">
            </div>
            <div class="home-user-ad">
                <img src="image/iklan2.png" alt="Iklan 2">
            </div>
        </div>
    </div>

    <div class="home-user-shop-by-categories">
        <h2>Shop By Categories :</h2>
        <div class="home-user-categories-container">
            <div class="home-user-category">
                <img src="image/Category-layout (1).png" alt="Category 1">
            </div>
            <div class="home-user-category">
                <img src="image/Category-layout (2).png" alt="Category 2">
            </div>
            <div class="home-user-category">
                <img src="image/Category-layout (3).png" alt="Category 3">
            </div>
            <div class="home-user-category">
                <img src="image/Category-layout (4).png" alt="Category 4">
            </div>
            <div class="home-user-category">
                <img src="image/Category-layout (5).png" alt="Category 5">
            </div>
        </div>
    </div>

    <div class="home-user-shop-by-products-newest">
        <h2>Newest product :</h2>
        <div class="home-product-newest-container d-flex flex-wrap justify-content-start">
            @forelse ($products->take(5) as $product)
                <a href="{{ Auth::check() ? route('products-all.show', $product->id) : route('login') }}"
                    class="home-product-newest-card">
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}"
                        class="home-product-newest-img-top">
                    <div class="home-product-newest-body text-center">
                        <h6 class="home-product-newest-title" style="color: black">{{ $product->name }}</h6>
                        <p class="home-product-newest-price">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="home-product-sales"
                            style="font-size: 11px; font-weight: bold; color: gray; margin-bottom: 1px; text-align: justify;">
                            Sold | {{ $product->sales }}</p>
                        @if (auth()->check())
                            <i class="fas fa-heart home-product-newest-wishlist-icon {{ in_array($product->id, $userWishlistIds) ? 'text-danger' : 'text-secondary' }}"
                                data-product-id="{{ $product->id }}"></i>
                        @endif
                    </div>
                </a>
            @empty
                <p>No products available at the moment.</p>
            @endforelse
        </div>
    </div>

    <div class="home-user-shop-by-brands">
        @foreach ($categories as $category)
            <h2>Featured {{ $category->name }}</h2>
            <div class="home-user-brands-container">
                @forelse ($category->brands as $brand)
                    <div class="home-user-brand">
                        <a href="{{ route('products-all.index', ['brand' => $brand->name]) }}">
                            <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="{{ $brand->name }}">
                        </a>
                    </div>
                @empty
                    <p>No brands available in this category.</p>
                @endforelse
            </div>
        @endforeach
    </div>
@endsection

@section('chat')
    @auth
        <style>
            .chat-wrapper {
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 9999;
            }

            .chatbox {
                display: none;
                width: 350px;
                height: 500px;
                background: white;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                flex-direction: column;
            }

            .chatbox.active {
                display: flex;
            }

            .chat-header {
                background-color: #6a64f1;
                color: white;
                padding: 12px 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .chat-messages {
                flex: 1;
                overflow-y: auto;
                padding: 16px;
                background: #f8f9fa;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .message {
                max-width: 80%;
                padding: 8px 12px;
                border-radius: 15px;
                font-size: 14px;
                animation: fadeIn 0.3s ease-in;
            }

            .user-message {
                background: #6a64f1;
                color: white;
                align-self: flex-end;
            }

            .bot-message {
                background: white;
                border: 1px solid #e0e0e0;
                align-self: flex-start;
            }

            .chat-input {
                display: flex;
                gap: 8px;
                padding: 16px;
                border-top: 1px solid #e0e0e0;
                background: white;
            }

            .chat-input input {
                flex: 1;
                padding: 8px 12px;
                border: 1px solid #e0e0e0;
                border-radius: 20px;
                outline: none;
                font-size: 14px;
            }

            .chat-input button {
                background: #6a64f1;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 20px;
                cursor: pointer;
                transition: background 0.2s;
            }

            .chat-input button:hover {
                background: #5751d8;
            }

            .chat-toggle-btn {
                width: 60px;
                height: 60px;
                background-color: #6a64f1;
                color: white;
                border-radius: 50%;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                transition: transform 0.3s ease;
            }

            .chat-toggle-btn:hover {
                transform: scale(1.05);
            }

            .chat-toggle-btn svg {
                width: 24px;
                height: 24px;
            }

            .chat-toggle-btn .close-icon {
                display: none;
            }

            .chat-toggle-btn.active .chat-icon {
                display: none;
            }

            .chat-toggle-btn.active .close-icon {
                display: block;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Scrollbar Styling */
            ::-webkit-scrollbar {
                width: 6px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        </style>

        <div class="chat-wrapper">
            <div class="chatbox" id="chatbox">
                <div class="chat-header">
                    <span>Live Chat Support</span>
                    <button onclick="toggleChat()" style="background: transparent; border: none;">
                        <svg width="20" height="20" fill="white" viewBox="0 0 20 20">
                            <path d="M2 2L18 18M18 2L2 18" stroke="white" stroke-width="2" />
                        </svg>
                    </button>
                </div>

                <div class="chat-messages" id="chatMessages">
                    <div class="message bot-message">Hello! How can I help you today? 😊</div>
                    <div class="message bot-message">We're here to answer your questions. Feel free to ask anything!</div>
                </div>

                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Type a message..." onkeypress="handleKeyPress(event)">
                    <button onclick="sendMessage()">Send</button>
                </div>
            </div>

            <button class="chat-toggle-btn" id="chatToggleBtn" onclick="toggleChat()">
                <span class="chat-icon">
                    <svg fill="white" viewBox="0 0 28 28">
                        <path
                            d="M19.833 14V3.5a1.167 1.167 0 0 0-1.167-1.167H3.5A1.167 1.167 0 0 0 2.333 3.5V19.833L7 15.167h11.666A1.167 1.167 0 0 0 19.833 14Z" />
                        <path
                            d="M24.5 7h-2.333v10.5H7v2.333a1.167 1.167 0 0 0 1.167 1.167H21l4.667 4.667V8.167A1.167 1.167 0 0 0 24.5 7Z" />
                    </svg>
                </span>
                <span class="close-icon">
                    <svg fill="white" viewBox="0 0 20 20">
                        <path d="M2 2L18 18M18 2L2 18" stroke="white" stroke-width="2" />
                    </svg>
                </span>
            </button>
        </div>

        <script>
    function toggleChat() {
        const chatbox = document.getElementById('chatbox');
        const toggleBtn = document.getElementById('chatToggleBtn');
        chatbox.classList.toggle('active');
        toggleBtn.classList.toggle('active');
    }

    function handleKeyPress(e) {
        if(e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    function sendMessage() {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        const chatMessages = document.getElementById('chatMessages');

        if(message) {
            // Add user message
            const userMessage = document.createElement('div');
            userMessage.className = 'message user-message';
            userMessage.textContent = message;
            chatMessages.appendChild(userMessage);

            input.value = '';

            // Simulate bot response
            setTimeout(() => {
                const botResponse = document.createElement('div');
                botResponse.className = 'message bot-message';
                botResponse.textContent = 'Thanks for your message! Our team will respond shortly.';
                chatMessages.appendChild(botResponse);

                // Auto-scroll to bottom
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 1000);

            // Auto-scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }
</script>
    @endauth
@endsection



@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ==================== BANNER SLIDER ====================
            const banner = document.querySelector('.home-user-banner');
            const banners = document.querySelectorAll('.home-user-banner-image');
            const prevButton = document.querySelector('.home-user-banner-prev');
            const nextButton = document.querySelector('.home-user-banner-next');
            let currentIndex = 0;

            function updateBannerPosition() {
                banner.style.transform = `translateX(-${currentIndex * 100}%)`;
            }

            function slideBanner() {
                currentIndex = (currentIndex + 1) % banners.length;
                updateBannerPosition();
            }

            function slideToPrev() {
                currentIndex = (currentIndex - 1 + banners.length) % banners.length;
                updateBannerPosition();
            }

            prevButton.addEventListener('click', slideToPrev);
            nextButton.addEventListener('click', slideBanner);
            setInterval(slideBanner, 5000);

            // ==================== WISHLIST HANDLER ====================
            $('.home-product-newest-wishlist-icon').on('click', function(event) {
                event.preventDefault();
                const productId = $(this).data('product-id');
                const $this = $(this);

                $.ajax({
                    url: '{{ route('wishlist.add') }}',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'added') {
                            $this.removeClass('text-secondary').addClass('text-danger');
                        } else if (response.status === 'removed') {
                            $this.removeClass('text-danger').addClass('text-secondary');
                        }
                        $('#for-badge-count-wishlist').text(response.wishlistCount);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                    }
                });
            });

            // ==================== HORIZONTAL SCROLL BRANDS ====================
            const brandsContainers = document.querySelectorAll('.home-user-brands-container');
            brandsContainers.forEach(container => {
                let isDragging = false;
                let startX;
                let scrollLeft;

                container.addEventListener('mousedown', (e) => {
                    isDragging = true;
                    startX = e.pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                });

                container.addEventListener('mouseleave', () => {
                    isDragging = false;
                });

                container.addEventListener('mouseup', () => {
                    isDragging = false;
                });

                container.addEventListener('mousemove', (e) => {
                    if (!isDragging) return;
                    e.preventDefault();
                    const x = e.pageX - container.offsetLeft;
                    const walk = (x - startX) * 2;
                    container.scrollLeft = scrollLeft - walk;
                });
            });
        });
    </script>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
@endpush
