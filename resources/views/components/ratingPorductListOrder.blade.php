<div class="modal fade" id="ratingModal{{ $order->id }}" tabindex="-1"
    aria-labelledby="ratingModalLabel{{ $order->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: white;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="ratingModalLabel{{ $order->id }}">
                    <i class="fas fa-star text-warning me-2"></i>Rate Products
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-info mb-4 p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                    <h6 class="text-muted mb-1">Order ID</h6>
                    <p class="fw-bold mb-0">{{ $order->unique_order_id }}</p>
                </div>

                <form action="{{ route('orders.rate', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    @foreach ($order->checkouts as $index => $checkout)
                        @php
                            $product = $checkout->product;
                            $mainImage =
                                $product && $product->images->count()
                                    ? $product->images->first()->image_path
                                    : 'product_images/default.png';
                            $productName = $product->name ?? 'Produk Tidak Tersedia';
                        @endphp

                        @if ($product)
                            <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                            <div class="product-rating-card mb-4 p-3"
                                style="border: 1px solid #e9ecef; border-radius: 12px; background-color: #ffffff;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="product-image-wrapper" style="flex-shrink: 0;">
                                        <img src="{{ asset('storage/' . ltrim($mainImage, '/')) }}"
                                            alt="{{ $productName }}"
                                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #e9ecef;">
                                    </div>
                                    <div class="product-rating-content" style="flex: 1;">
                                        <h6 class="product-name mb-2 fw-semibold">{{ $productName }}</h6>

                                        <div class="rating-section mb-3">
                                            <label class="form-label mb-2 fw-semibold text-dark">Rating</label>
                                            <div class="star-rating d-flex gap-1"
                                                data-product-id="{{ $product->id }}">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star star-icon" data-rating="{{ $i }}"
                                                        style="font-size: 24px; color: #ddd; cursor: pointer; transition: color 0.2s ease;"></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="ratings[{{ $product->id }}]"
                                                class="rating-input" value="0">
                                        </div>

                                        <div class="comment-section">
                                            <label class="form-label mb-2 fw-semibold text-dark">Comment</label>
                                            <textarea name="comments[{{ $product->id }}]" class="form-control" rows="3"
                                                placeholder="Share your experience with this product..."
                                                style="border: 1px solid #ced4da; border-radius: 8px; resize: vertical;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="modal-footer border-0 pt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-paper-plane me-2"></i>Submit Rating
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('pemai/css/ratingProduct.css') }}">
<script src="{{ asset('js/ratingProduct.js') }}"></script>
