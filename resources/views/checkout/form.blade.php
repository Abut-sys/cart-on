@extends('layouts.index')

@section('content')
    <div class="container py-4">
        <h2 class="checkout-title mb-4 text-center">Checkout</h2>

        <div class="checkout-container">
            <div class="row g-4">
                <div class="col-lg-8 col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Detail Produk</h5>
                        </div>
                        <div class="card-body">
                            @if (isset($carts) && $carts->count() > 0)
                                @foreach ($carts as $cart)
                                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                        <div class="checkout-product-image me-3 flex-shrink-0">
                                            <img src="{{ asset('storage/' . ($cart->product->images->first()->image_path ?? 'images/default.jpg')) }}"
                                                alt="{{ $cart->product->name }}" class="img-fluid rounded"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                        <div class="checkout-product-info flex-grow-1">
                                            <h5 class="checkout-product-name mb-1">{{ $cart->product->name }}</h5>
                                            @php
                                                $subVariant = $cart->product->subVariant
                                                    ->where('color', $cart->color)
                                                    ->where('size', $cart->size)
                                                    ->first();
                                            @endphp
                                            @if ($subVariant)
                                                <p class="text-muted mb-1">Color: {{ $subVariant->color }}</p>
                                                <p class="text-muted mb-1">Size: {{ $subVariant->size }}</p>
                                            @endif
                                            <p class="mb-1">Quantity: <strong
                                                    class="text-dark">{{ $cart->quantity }}</strong></p>
                                            <h6 class="checkout-product-price text-primary">
                                                Rp{{ number_format($cart->product->price, 2) }}
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif(isset($product))
                                <div class="d-flex align-items-start pb-3">
                                    <div class="checkout-product-image me-3 flex-shrink-0">
                                        <img src="{{ asset('storage/' . ($product->images->first()->image_path ?? 'images/default.jpg')) }}"
                                            alt="{{ $product->name }}" class="img-fluid rounded"
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                    <div class="checkout-product-info flex-grow-1">
                                        <h5 class="checkout-product-name mb-1">{{ $product->name }}</h5>
                                        @if (isset($variant))
                                            <p class="text-muted mb-1">Color: {{ $variant->color ?? 'N/A' }}</p>
                                            <p class="text-muted mb-1">Size: {{ $variant->size ?? 'N/A' }}</p>
                                        @endif
                                        <p class="mb-1">Quantity: <strong class="text-dark">{{ $quantity }}</strong>
                                        </p>
                                        <h6 class="checkout-product-price text-primary">
                                            Rp{{ number_format($product->price, 2) }}
                                        </h6>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">Tidak ada produk yang dipilih.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Payment Summary & Address Section --}}
                <div class="col-lg-4 col-md-12">
                    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        {{-- Hidden inputs untuk produk --}}
                        @if (isset($carts) && $carts->count() > 0)
                            @foreach ($carts as $cart)
                                <input type="hidden" name="selected-products[]" value="{{ $cart->id }}">
                            @endforeach
                        @elseif(isset($product))
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" value="{{ $variant->id ?? '' }}">
                            <input type="hidden" name="quantity" value="{{ $quantity }}">
                        @endif
                        <input type="hidden" name="raw_product_total" id="raw-product-total"
                            value="{{ $rawProductTotal }}">
                        <input type="hidden" name="voucher_code" id="voucher_code_hidden">
                        <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                        <input type="hidden" name="courier" id="selected_courier">
                        <input type="hidden" name="shipping_service" id="selected_shipping_service">

                        {{-- Shipping Address Section --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Alamat Pengiriman</h5>
                            </div>
                            <div class="card-body">
                                @if ($addresses->isEmpty())
                                    <p class="text-danger">Belum ada alamat tersedia. Mohon tambahkan alamat di profil Anda.
                                    </p>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">Tambah
                                        Alamat</a>
                                @else
                                    <div class="mb-3">
                                        <label for="address_id" class="form-label fw-bold">Pilih Alamat:</label>
                                        <select name="address_id" id="address_id"
                                            class="form-select @error('address_id') is-invalid @enderror">
                                            <option value="" disabled selected>Pilih Alamat Pengiriman</option>
                                            @foreach ($addresses as $address)
                                                <option value="{{ $address->id }}"
                                                    {{ $selectedAddressId == $address->id || old('address_id') == $address->id ? 'selected' : '' }}>
                                                    {{ $address->address_line1 }}, {{ $address->city }},
                                                    {{ $address->province }}, {{ $address->postal_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('address_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="courier" class="form-label fw-bold">Pilih Kurir:</label>
                                        <select name="courier" id="courier"
                                            class="form-select @error('courier') is-invalid @enderror">
                                            <option value="" disabled selected>Pilih Kurir</option>
                                            <option value="jne" {{ old('courier') == 'jne' ? 'selected' : '' }}>JNE
                                            </option>
                                            <option value="tiki" {{ old('courier') == 'tiki' ? 'selected' : '' }}>TIKI
                                            </option>
                                            <option value="pos" {{ old('courier') == 'pos' ? 'selected' : '' }}>POS
                                            </option>
                                        </select>
                                        @error('courier')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="shipping_service" class="form-label fw-bold">Layanan Pengiriman:</label>
                                        <select name="shipping_service" id="shipping_service"
                                            class="form-select @error('shipping_service') is-invalid @enderror" disabled>
                                            <option value="" disabled selected>Pilih Kurir Terlebih Dahulu</option>
                                        </select>
                                        @error('shipping_service')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Voucher Code Section --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Kode Voucher</h5>
                            </div>
                            <div class="card-body">
                                <div class="input-group mb-2">
                                    <input type="text" name="voucher_code_input" id="voucher_code_input"
                                        class="form-control" placeholder="Masukkan kode voucher"
                                        value="{{ old('voucher_code_input') }}">
                                    <button type="button" id="apply-voucher-btn"
                                        class="btn btn-outline-primary">Apply</button>
                                </div>
                                <small id="voucher-error-message" class="text-danger d-block mt-2"></small>
                                <small id="voucher-success-message" class="text-success d-block mt-2"></small>
                            </div>
                        </div>

                        {{-- Payment Summary Section --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Ringkasan Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <p class="d-flex justify-content-between">Harga Produk:
                                    <span class="fw-bold">Rp<span
                                            id="product-price-summary">{{ number_format($rawProductTotal, 2) }}</span></span>
                                </p>
                                <p class="d-flex justify-content-between">Diskon Voucher:
                                    <span class="fw-bold text-danger">- Rp<span
                                            id="discount-amount-summary">0.00</span></span>
                                </p>
                                <p class="d-flex justify-content-between">Biaya Pengiriman:
                                    <span class="fw-bold">Rp<span id="shipping-cost-summary">0.00</span></span>
                                </p>
                                <hr>
                                <h4 class="d-flex justify-content-between text-primary">Total Pembayaran:
                                    <span class="fw-bold">Rp<span
                                            id="final-total-price-display">{{ number_format($rawProductTotal, 2) }}</span></span>
                                </h4>
                            </div>
                        </div>

                        <button id="pay-button" type="button" class="btn btn-success btn-lg w-100 mt-3">Bayar
                            Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rawProductTotalInput = document.getElementById('raw-product-total');
            const voucherCodeInput = document.getElementById('voucher_code_input');
            const voucherCodeHiddenInput = document.getElementById('voucher_code_hidden');
            const applyVoucherBtn = document.getElementById('apply-voucher-btn');
            const voucherError = document.getElementById('voucher-error-message');
            const voucherSuccess = document.getElementById('voucher-success-message');

            const addressSelect = document.getElementById('address_id');
            const courierSelect = document.getElementById('courier');
            const shippingServiceSelect = document.getElementById('shipping_service');
            const shippingCostInput = document.getElementById('shipping_cost');

            const productPriceSummary = document.getElementById('product-price-summary');
            const discountAmountSummary = document.getElementById('discount-amount-summary');
            const shippingCostSummary = document.getElementById('shipping-cost-summary');
            const finalTotalPriceDisplay = document.getElementById('final-total-price-display');

            const payButton = document.getElementById('pay-button');
            const checkoutForm = document.getElementById('checkout-form');

            const originalRawProductTotal = parseFloat(rawProductTotalInput.value);

            let currentDiscountAmount = 0;
            let currentShippingCost = 0;

            function formatRupiah(amount) {
                return parseFloat(amount).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function updateSummaryDisplay() {
                const finalTotal = originalRawProductTotal - currentDiscountAmount + currentShippingCost;

                productPriceSummary.textContent = formatRupiah(originalRawProductTotal);
                discountAmountSummary.textContent = formatRupiah(currentDiscountAmount);
                shippingCostSummary.textContent = formatRupiah(currentShippingCost);
                finalTotalPriceDisplay.textContent = formatRupiah(finalTotal);
            }

            async function fetchShippingOptions() {
                const selectedAddressId = addressSelect.value;
                const selectedCourier = courierSelect.value;

                shippingServiceSelect.innerHTML = '<option value="" disabled selected>Memuat...</option>';
                shippingServiceSelect.disabled = true;
                currentShippingCost = 0;
                shippingCostInput.value = 0;
                updateSummaryDisplay();

                if (!selectedAddressId || !selectedCourier) {
                    shippingServiceSelect.innerHTML =
                        '<option value="" disabled selected>Pilih alamat dan kurir terlebih dahulu</option>';
                    return;
                }

                try {
                    const response = await fetch('{{ route('get-shipping-cost') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            address_id: selectedAddressId,
                            courier: selectedCourier
                        })
                    });
                    const data = await response.json();

                    shippingServiceSelect.innerHTML = '';
                    if (response.ok && data && data.length > 0) {
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
                                `${service.service} - ${service.description} - Rp${formatRupiah(service.cost)}`;
                            option.dataset.cost = service.cost;
                            shippingServiceSelect.appendChild(option);
                        });
                        shippingServiceSelect.disabled = false;
                    } else if (data && data.error) {
                        shippingServiceSelect.innerHTML =
                            `<option value="" disabled selected>${data.error}</option>`;
                    } else {
                        shippingServiceSelect.innerHTML =
                            `<option value="" disabled selected>Tidak ada layanan yang tersedia</option>`;
                    }
                } catch (error) {
                    console.error('Error fetching shipping options:', error);
                    shippingServiceSelect.innerHTML =
                        `<option value="" disabled selected>Gagal memuat layanan</option>`;
                } finally {
                    updateSummaryDisplay();
                }
            }

            async function applyVoucher() {
                const voucherCode = voucherCodeInput.value.trim();

                voucherError.textContent = '';
                voucherSuccess.textContent = '';
                currentDiscountAmount = 0;
                voucherCodeHiddenInput.value = '';
                updateSummaryDisplay();

                if (voucherCode === "") {
                    voucherError.textContent = 'Harap masukkan kode voucher.';
                    return;
                }

                try {
                    const response = await fetch('{{ route('voucher.check') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            voucher_code: voucherCode,
                            raw_product_total: originalRawProductTotal
                        })
                    });
                    const data = await response.json();

                    if (data.success) {
                        currentDiscountAmount = data.discount_amount;
                        voucherCodeHiddenInput.value = voucherCode;
                        voucherSuccess.textContent = 'Voucher berhasil diterapkan! Diskon: Rp' + formatRupiah(
                            data.discount_amount);
                    } else {
                        voucherError.textContent = data.message || 'Kode voucher tidak valid.';
                        voucherCodeHiddenInput.value = '';
                    }
                } catch (error) {
                    console.error('Error applying voucher:', error);
                    voucherError.textContent = 'Terjadi kesalahan saat memeriksa voucher.';
                    voucherCodeHiddenInput.value = '';
                } finally {
                    updateSummaryDisplay();
                }
            }

            applyVoucherBtn.addEventListener('click', applyVoucher);

            addressSelect.addEventListener('change', fetchShippingOptions);
            courierSelect.addEventListener('change', fetchShippingOptions);

            shippingServiceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.dataset.cost) {
                    currentShippingCost = parseFloat(selectedOption.dataset.cost);
                    shippingCostInput.value = currentShippingCost;
                    document.getElementById('selected_courier').value = courierSelect.value;
                    document.getElementById('selected_shipping_service').value = shippingServiceSelect
                        .value;
                } else {
                    currentShippingCost = 0;
                    shippingCostInput.value = 0;
                }
                updateSummaryDisplay();
            });

            payButton.addEventListener('click', async function() {
                if (!addressSelect.value) {
                    alert('Harap pilih alamat pengiriman.');
                    return;
                }
                if (!courierSelect.value) {
                    alert('Harap pilih kurir.');
                    return;
                }
                if (!shippingServiceSelect.value) {
                    alert('Harap pilih jenis layanan pengiriman.');
                    return;
                }

                document.getElementById('selected_courier').value = courierSelect.value;
                document.getElementById('selected_shipping_service').value = shippingServiceSelect
                    .value;

                try {
                    const formData = new FormData(checkoutForm);
                    const response = await fetch(checkoutForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });
                    const data = await response.json();

                    if (data.success && data.snapToken) {
                        snap.pay(data.snapToken, {
                            onSuccess: async function(result) {
                                try {
                                    const response = await fetch('/status/update-payment', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]')
                                                .getAttribute('content'),
                                        },
                                        body: JSON.stringify({
                                            order_id: result
                                                .order_id,
                                            payment_status: result
                                                .transaction_status
                                        }),
                                    });

                                    const data = await response.json();

                                    if (response.ok) {
                                        window.location.href = '{{ route('orders.history') }}';
                                    } else {
                                        alert('Gagal update status pembayaran: ' + (data
                                            .message || 'Error tidak dikenal'));
                                    }
                                } catch (error) {
                                    console.error('Error update status payment:', error);
                                    alert(
                                        'Terjadi kesalahan saat mengupdate status pembayaran.'
                                        );
                                }
                            },
                            onPending: function(result) {
                                window.location.href =
                                    '{{ route('home.index') }}';
                            },
                            onError: function(result) {
                                console.error(result);
                                alert('Pembayaran Gagal: ' + result.status_message);
                            },
                            onClose: function() {
                                window.location.href = '{{ route('orders.pending') }}';
                            }
                        });
                    } else {
                        alert('Gagal memproses pesanan: ' + (data.message ||
                            'Terjadi kesalahan tidak dikenal.'));
                        if (data.errors) {
                            let errorMessages = '';
                            for (const key in data.errors) {
                                errorMessages += data.errors[key].join('\n') + '\n';
                            }
                            alert('Detail Error:\n' + errorMessages);
                        }
                    }
                } catch (error) {
                    console.error('Error submitting checkout form:', error);
                    alert('Terjadi kesalahan saat membuat transaksi. Silakan coba lagi.');
                }
            });


            updateSummaryDisplay();
            if (addressSelect.value && courierSelect.value) {
                fetchShippingOptions();
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
