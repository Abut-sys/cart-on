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
                        <p>Shipping Price: <span id="shipping-cost-summary" class="float-end">Rp0.00</span></p>
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
                        <small id="voucher-success-message" class="text-success d-block mt-2"></small>
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
                    <input type="hidden" name="total_price" id="final-total-price" value="{{ $totalPrice }}">
                    <input type="hidden" name="voucher_code" id="voucher_code_hidden" value="{{ old('voucher_code') }}">
                    <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                    <input type="hidden" name="courier" id="selected_courier">
                    <input type="hidden" name="shipping_service" id="selected_shipping_service">

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
                                    <option value="" disabled selected>Select Shipping Address</option>
                                    @foreach ($addresses as $address)
                                        <option value="{{ $address->id }}"
                                            {{ old('address_id') == $address->id ? 'selected' : '' }}>
                                            {{ $address->address_line1 }}, {{ $address->city }},
                                            {{ $address->state }}, {{ $address->postal_code }}
                                        </option>
                                    @endforeach
                                </select>

                                <label for="courier" class="form-label mt-3">Courier:</label>
                                <select name="courier" id="courier" class="form-select">
                                    <option value="" disabled selected>Select Courier</option>
                                    <option value="jne" {{ old('courier') == 'jne' ? 'selected' : '' }}>JNE</option>
                                    <option value="tiki" {{ old('courier') == 'tiki' ? 'selected' : '' }}>TIKI</option>
                                    <option value="pos" {{ old('courier') == 'pos' ? 'selected' : '' }}>POS</option>
                                </select>

                                <label for="shipping_service" class="form-label mt-3">Shipping Service:</label>
                                <select name="shipping_service" id="shipping_service" class="form-select" disabled>
                                    <option value="" disabled selected>Select Courier First</option>
                                </select>
                            </div>
                        @endif
                    </div>

                    <button id="pay-button" type="button" class="btn btn-primary w-100 mt-3">Buy Now</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const applyVoucherBtn = document.getElementById('apply-voucher-btn');
            const voucherCodeInput = document.getElementById('voucher_code');
            const voucherCodeHiddenInput = document.getElementById('voucher_code_hidden');
            const totalPriceDisplay = document.getElementById('total-price');
            const finalTotalPriceInput = document.getElementById('final-total-price');
            const voucherError = document.getElementById('voucher-error-message');
            const voucherSuccess = document.getElementById('voucher-success-message');

            applyVoucherBtn.addEventListener('click', function() {
                const voucherCode = voucherCodeInput.value;
                const currentTotalPrice = parseFloat(finalTotalPriceInput.value);

                voucherError.textContent = '';
                voucherSuccess.textContent = '';

                if (voucherCode === "") {
                    voucherError.textContent = 'Harap masukkan kode voucher.';
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
                            total_price: currentTotalPrice
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const newTotal = data.new_total;
                            totalPriceDisplay.textContent = 'Rp' + newTotal.toLocaleString();
                            finalTotalPriceInput.value = newTotal;
                            voucherCodeHiddenInput.value = voucherCode;
                            voucherSuccess.textContent = 'Voucher berhasil diterapkan!';
                        } else {
                            voucherError.textContent = data.message || 'Kode voucher tidak valid.';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        voucherError.textContent = 'Terjadi kesalahan saat memeriksa voucher.';
                    });
            });

            const payButton = document.getElementById('pay-button');
            payButton.addEventListener('click', function() {
                const selectedAddress = document.getElementById('address_id').value;
                const selectedCourier = document.getElementById('courier').value;
                const selectedService = document.getElementById('shipping_service').value;
                const shippingCost = document.getElementById('shipping_cost').value;


                if (!selectedAddress) {
                    alert('Harap pilih alamat pengiriman.');
                    return;
                }
                if (!selectedCourier) {
                    alert('Harap pilih kurir.');
                    return;
                }
                if (!selectedService) {
                    alert('Harap pilih jenis layanan pengiriman.');
                    return;
                }

                document.getElementById('selected_courier').value = selectedCourier;
                document.getElementById('selected_shipping_service').value = selectedService;

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
                                            voucher_code: voucherCodeHiddenInput
                                                .value
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(
                                            "Voucher usage updated successfully"
                                            );
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert(
                                            'Ada kesalahan dalam memproses voucher.'
                                            );
                                    });
                            }, 1000); // Delay
                        }, 100); // Delay
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

            const addressSelect = document.getElementById('address_id');
            const courierSelect = document.getElementById('courier');
            const shippingServiceSelect = document.getElementById('shipping_service');
            const shippingCostInput = document.getElementById('shipping_cost');
            const shippingCostSummary = document.getElementById('shipping-cost-summary');
            const totalPriceDisplayElement = document.getElementById('total-price');

            function updateShippingOptions() {
                const selectedAddressId = addressSelect.value;
                const selectedCourier = courierSelect.value;

                if (!selectedAddressId || !selectedCourier) {
                    shippingServiceSelect.innerHTML =
                        '<option value="" disabled selected>Pilih kurir terlebih dahulu</option>';
                    shippingServiceSelect.disabled = true;
                    shippingCostInput.value = 0;
                    shippingCostSummary.textContent = 'Rp0.00';
                    updateFinalTotal();
                    return;
                }

                shippingServiceSelect.innerHTML = '<option value="" disabled selected>Memuat...</option>';
                shippingServiceSelect.disabled = true;

                fetch('{{ route('get-shipping-cost') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            address_id: selectedAddressId,
                            courier: selectedCourier
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        shippingServiceSelect.innerHTML = '';
                        if (data && data.length > 0) {
                            const defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.disabled = true;
                            defaultOption.selected = true;
                            defaultOption.textContent = 'Pilih Jenis Layanan';
                            shippingServiceSelect.appendChild(defaultOption);
                            data.forEach(service => {
                                const option = document.createElement('option');
                                option.value = service.service;
                                option.textContent =
                                    `${service.service} - ${service.description} - Rp${service.cost.toLocaleString()}`;
                                shippingServiceSelect.appendChild(option);
                            });
                            shippingServiceSelect.disabled = false;
                        } else if (data && data.error) {
                            shippingServiceSelect.innerHTML =
                                `<option value="" disabled selected>${data.error}</option>`;
                            shippingServiceSelect.disabled = true;
                            shippingCostInput.value = 0;
                            shippingCostSummary.textContent = 'Rp0.00';
                            updateFinalTotal();
                        } else {
                            shippingServiceSelect.innerHTML =
                                '<option value="" disabled selected>Tidak ada layanan yang tersedia</option>';
                            shippingServiceSelect.disabled = true;
                            shippingCostInput.value = 0;
                            shippingCostSummary.textContent = 'Rp0.00';
                            updateFinalTotal();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching shipping options:', error);
                        shippingServiceSelect.innerHTML =
                            '<option value="" disabled selected>Gagal memuat layanan</option>';
                        shippingServiceSelect.disabled = true;
                        shippingCostInput.value = 0;
                        shippingCostSummary.textContent = 'Rp0.00';
                        updateFinalTotal();
                    });
            }

            addressSelect.addEventListener('change', updateShippingOptions);
            courierSelect.addEventListener('change', updateShippingOptions);

            shippingServiceSelect.addEventListener('change', function() {
                const selectedService = this.value;
                const selectedCourier = courierSelect.value;
                const selectedAddressId = addressSelect.value;

                if (selectedService && selectedCourier && selectedAddressId) {
                    fetch('{{ route('get-shipping-cost') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                address_id: selectedAddressId,
                                courier: selectedCourier,
                                service: selectedService
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.cost) {
                                const cost = data.cost;
                                shippingCostInput.value = cost;
                                shippingCostSummary.textContent = 'Rp' + cost.toLocaleString();
                                updateFinalTotal();
                            } else {
                                alert('Gagal mengambil biaya pengiriman.');
                                shippingCostInput.value = 0;
                                shippingCostSummary.textContent = 'Rp0.00';
                                updateFinalTotal();
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching shipping cost:', error);
                            alert('Terjadi kesalahan saat menghitung biaya pengiriman.');
                            shippingCostInput.value = 0;
                            shippingCostSummary.textContent = 'Rp0.00';
                            updateFinalTotal();
                        });
                } else {
                    shippingCostInput.value = 0;
                    shippingCostSummary.textContent = 'Rp0.00';
                    updateFinalTotal();
                }
            });

            function updateFinalTotal() {
                const productTotal = parseFloat('{{ $totalPrice }}');
                const shippingCost = parseFloat(shippingCostInput.value) || 0;
                const finalTotal = productTotal + shippingCost;
                totalPriceDisplayElement.textContent = 'Rp' + finalTotal.toLocaleString();
                finalTotalPriceInput.value = finalTotal;
            }

            if (addressSelect.value && courierSelect.value) {
                updateShippingOptions();
            }
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
