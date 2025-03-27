@extends('layouts.index')

@section('content')
    <h2 class="checkout-title mb-4">Checkout</h2>

    <div class="checkout-container">
        <div class="checkout-content row">
            <div class="checkout-details col-lg-8 col-md-12">
                <div class="checkout-card mb-4">
                    <div class="checkout-card-header">
                        <h5>Product Details</h5>
                    </div>
                    <div class="checkout-card-body">
                        @if (isset($carts) && count($carts) > 0)
                            @foreach ($carts as $cart)
                                <div class="checkout-card-body d-flex align-items-start mb-3">
                                    <div class="checkout-product-image me-3">
                                        <img src="{{ asset('storage/' . ($cart->product->images->first()->image_path ?? 'default.jpg')) }}"
                                            alt="{{ $cart->product->name }}" class="img-fluid rounded">
                                    </div>
                                    <div class="checkout-product-info">
                                        <h4 class="checkout-product-name">{{ $cart->product->name }}</h4>
                                        <p class="checkout-product-color">
                                            Color:
                                            {{ optional($cart->variant ?? $cart->product->subVariant->first())->color ?? 'N/A' }}
                                        </p>
                                        <p class="checkout-product-size">
                                            Size:
                                            {{ optional($cart->variant ?? $cart->product->subVariant->first())->size ?? 'N/A' }}
                                        </p>
                                        <p class="checkout-product-quantity">Quantity: {{ $cart->quantity }}</p>
                                        <h4 class="checkout-product-price">Rp{{ number_format($cart->product->price, 2) }}
                                        </h4>
                                    </div>
                                </div>
                            @endforeach
                        @elseif(isset($product))
                            <div class="checkout-card-body d-flex align-items-start">
                                <div class="checkout-product-image me-3">
                                    <img src="{{ asset('storage/' . ($product->images->first()->image_path ?? 'default.jpg')) }}"
                                        alt="{{ $product->name }}" class="img-fluid rounded">
                                </div>
                                <div class="checkout-product-info">
                                    <h4 class="checkout-product-name">{{ $product->name }}</h4>
                                    <p class="checkout-product-color">Color: {{ $variant->color ?? 'N/A' }}</p>
                                    <p class="checkout-product-size">Size: {{ $variant->size ?? 'N/A' }}</p>
                                    <p class="checkout-product-quantity">Quantity: {{ $quantity }}</p>
                                    <h4 class="checkout-product-price">Rp{{ number_format($product->price, 2) }}</h4>
                                </div>
                            </div>
                        @else
                            <p>No products found.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="checkout-summary col-lg-4 col-md-12">
                <div class="checkout-card mb-4">
                    <div class="checkout-card-header">
                        <h5>Payment Summary</h5>
                    </div>
                    <div class="checkout-card-body">
                        <p>Product Price: <span class="float-end">Rp{{ number_format($totalPrice, 2) }}</span></p>
                        <h4>Total: <span id="total-price" class="float-end">Rp{{ number_format($totalPrice, 2) }}</span>
                        </h4>
                    </div>
                </div>

                <div class="checkout-card mb-4">
                    <div class="checkout-card-header">
                        <h5>Voucher Code</h5>
                    </div>
                    <div class="checkout-card-body">
                        <input type="text" name="voucher_code" id="voucher_code" class="form-control mb-2"
                            placeholder="Masukkan kode voucher" value="{{ old('voucher_code') }}">
                        <button type="button" id="apply-voucher-btn" class="btn btn-secondary w-100">Apply</button>
                        <small id="voucher-error-message" class="text-danger d-block mt-2">
                            @if (session('error'))
                                {{ session('error') }}
                            @endif
                        </small>
                    </div>
                </div>

                <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    @if (isset($carts) && count($carts) > 0)
                        @foreach ($carts as $cart)
                            <input type="hidden" name="selected-products[]" value="{{ $cart->id }}">
                        @endforeach
                    @elseif(isset($product))
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" value="{{ $variant->id ?? '' }}">
                        <input type="hidden" name="quantity" value="{{ $quantity }}">
                    @endif
                    <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                    <input type="hidden" name="voucher_code" id="voucher_code_hidden" value="{{ old('voucher_code') }}">

                    <div class="checkout-card mb-4">
                        <div class="checkout-card-header">
                            <h5>Shipping Address</h5>
                        </div>
                        @if ($addresses->isEmpty())
                            <p>No addresses available.</p>
                        @else
                            <div class="checkout-card-body">
                                <label for="address_id" class="form-label">Select Address:</label>
                                <select name="address_id" id="address_id" class="form-select">
                                    @foreach ($addresses as $address)
                                        <option value="{{ $address->id }}"
                                            {{ old('address_id') == $address->id ? 'selected' : '' }}>
                                            {{ $address->address_line1 }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <label for="shipping_method" class="form-label mt-3">Shipping Method:</label>
                        <select name="shipping_method" id="shipping_method" class="form-select">
                            <option value="standard" {{ old('shipping_method') == 'standard' ? 'selected' : '' }}>Standard
                            </option>
                            <option value="express" {{ old('shipping_method') == 'express' ? 'selected' : '' }}>Express
                            </option>
                        </select>
                    </div>
            </div>

            <button id="pay-button" type="button" class="btn btn-primary w-100 mt-3">Pay Now</button>
            </form>
        </div>
    </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        document.getElementById('apply-voucher-btn').addEventListener('click', function() {
            var voucherCode = document.getElementById('voucher_code').value;
            var totalPrice = {{ $totalPrice }};

            var errorMessageElement = document.getElementById('voucher-error-message');
            errorMessageElement.textContent = '';

            if (voucherCode === "") {
                errorMessageElement.textContent = 'Please enter a voucher code.';
                return;
            }

            fetch('{{ route('voucher.check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        voucher_code: voucherCode,
                        total_price: totalPrice
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var newTotal = data.new_total;

                        document.getElementById('total-price').innerText = 'Rp' + newTotal.toLocaleString();
                        document.querySelector('input[name="total_price"]').value = newTotal;
                        document.getElementById('voucher_code_hidden').value =
                            voucherCode;

                        errorMessageElement.textContent = '';
                    } else {
                        errorMessageElement.textContent = data.message ||
                            'Voucher is invalid, expired, or already used.';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorMessageElement.textContent = 'An error occurred while processing the voucher.';
                });
        });
        document.getElementById('pay-button').addEventListener('click', function() {
            var finalPrice = document.querySelector('input[name="total_price"]').value;

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    document.getElementById('checkout-form').submit();

                    setTimeout(function() {
                        window.location.href = '{{ route('home.index') }}';

                        setTimeout(function() {
                            fetch('{{ route('voucher.updateUsage') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        voucher_code: document
                                            .getElementById(
                                                'voucher_code_hidden').value
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log(
                                        "Voucher usage updated successfully");
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Ada kesalahan dalam memproses voucher.');
                                });
                        }, 1000); // Delay to ensure redirection happens before voucher update
                    }, 100); // Delay to allow form submission before redirection
                },
                onPending: function(result) {
                    alert("Pembayaran sedang diproses.");
                },
                onError: function(result) {
                    console.error(result);
                    alert('Pembayaran gagal');
                }
            });
        });
    </script>

    <style>
        .checkout-container {
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .checkout-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .checkout-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .checkout-card-header {
            background-color: #f1f1f1;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }

        .checkout-card-body {
            padding: 15px;
        }

        .checkout-product-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }

        .checkout-product-info h4 {
            font-size: 18px;
            font-weight: bold;
        }

        .checkout-summary h4 {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
        }

        .checkout-discount {
            margin-top: 10px;
            font-size: 14px;
            color: #6c757d;
        }

        .checkout-discount .float-end {
            color: #dc3545;
        }


        @media (max-width: 768px) {
            .checkout-content {
                flex-direction: column;
            }
        }
    </style>
@endsection
