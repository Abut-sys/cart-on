@extends('layouts.index')

@section('content')
    <h2 class="checkout-title mb-4">Checkout</h2>

    <div class="checkout-content row">
        <div class="checkout-details col-md-8">
            <div class="checkout-card mb-4">
                <div class="checkout-card-header">
                    <h5>Product Details</h5>
                </div>
                <div class="checkout-card-body d-flex align-items-start">
                    <div class="checkout-product-image">
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                            class="img-fluid rounded product-user-show-main-image">
                    </div>
                    <div class="checkout-product-info">
                        <h3 class="checkout-product-name">Product: {{ $product->name }}</h3>
                        <p class="checkout-product-color">Color: {{ $variant->color }}</p>
                        <p class="checkout-product-size">Size: {{ $variant->size }}</p>
                        <p class="checkout-product-quantity">Quantity: {{ $quantity }}</p>
                        <h4 class="checkout-product-price">Price: Rp{{ number_format($product->price, 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="checkout-card mb-4">
                <div class="checkout-card-header">
                    <h5>Shipping Address</h5>
                </div>
                <div class="checkout-card-body">
                    <label for="address_id" class="checkout-form-label">Select Address:</label>
                    <select name="address_id" id="address_id" class="checkout-form-control" required>
                        @foreach ($addresses as $address)
                            <option value="{{ $address->id }}" {{ old('address_id') == $address->id ? 'selected' : '' }}>
                                {{ $address->address_line1 }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="checkout-summary col-md-4">
            <div class="checkout-card mb-4">
                <div class="checkout-card-header">
                    <h5>Payment Summary</h5>
                </div>
                <div class="checkout-card-body">
                    <p class="checkout-summary-item">Product Price: <span
                            class="checkout-summary-value">Rp{{ number_format($product->price, 2) }}</span></p>
                    <p class="checkout-summary-item">Quantity: <span
                            class="checkout-summary-value">{{ $quantity }}</span></p>
                    <hr>
                    <h4 class="checkout-total-price">Total Price: Rp{{ number_format($totalPrice, 2) }}</h4>
                </div>
            </div>

            <div class="checkout-card mb-4">
                <div class="checkout-card-header">
                    <h5>Voucher Code</h5>
                </div>
                <div class="checkout-card-body">
                    <input type="text" name="voucher_code" id="voucher_code" class="checkout-form-control mb-3"
                        placeholder="Enter your voucher code">
                    <button type="button" id="apply-voucher-btn"
                        class="checkout-btn checkout-btn-secondary w-100">Apply</button>
                </div>
            </div>

            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                <input type="hidden" name="quantity" value="{{ $quantity }}">
                <input type="hidden" name="total_price" value="{{ $totalPrice }}">

                <button id="pay-button" type="button" class="btn btn-primary w-100 mt-3">Pay Now</button>
            </form>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = "{{ Route('home.index') }}";
                },
                onPending: function(result) {
                    alert("Payment is pending.");
                },
                onError: function(result) {
                    console.log(result);
                    alert('Payment failed');
                }
            });
        });
    </script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .checkout-title {
            text-align: left;
            font-size: 30px;
            font-weight: bold;
        }

        .checkout-content {
            display: flex;
            gap: 2rem;
        }

        .checkout-details {
            flex: 2;
        }

        .checkout-summary {
            flex: 1;
        }

        /* Card Container */
        .checkout-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .checkout-card-header {
            background-color: #f5f5f5;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
            font-size: 1rem;
            font-weight: bold;
        }

        .checkout-card-body {
            padding: 1rem;
        }

        /* Product Details */
        .checkout-product {
            display: flex;
            align-items: flex-start;
        }

        .checkout-product-image img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 20px;
        }

        .checkout-product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .checkout-product-info h4 {
            margin-bottom: 8px;
            font-size: 1rem;
            font-weight: bold;
            color: #333;
        }

        .checkout-product-info p {
            margin: 4px 0;
            font-size: 0.875rem;
            color: #555;
        }

        .checkout-product-price {
            margin-top: 12px;
            font-size: 1.25rem;
            font-weight: bold;
            color: #007bff;
        }

        /* Shipping Address */
        .checkout-form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        /* Summary Section */
        .checkout-summary {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 1rem;
        }

        .checkout-summary-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #333;
        }

        .checkout-summary-item span {
            font-weight: bold;
        }

        .checkout-total-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
            text-align: right;
            margin-top: 1rem;
        }

        .checkout-btn {
            display: inline-block;
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            margin-top: 1rem;
        }

        .checkout-btn-secondary {
            background-color: #6c757d;
        }

        .checkout-btn:hover {
            background-color: #0056b3;
        }

        /* Voucher Input */
        .voucher-input {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .voucher-input input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .voucher-input button {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: bold;
            color: #fff;
            background-color: #6c757d;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .voucher-input button:hover {
            background-color: #5a6268;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .checkout-content {
                flex-direction: column;
            }

            .checkout-product-image img {
                width: 80px;
                height: 80px;
            }
        }
    </style>
@endsection
