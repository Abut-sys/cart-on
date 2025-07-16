@extends('layouts.index')

@section('title', 'checkout product')

@section('content')
    <div class="container py-5">
        <div class="chk-container">
            <div class="row g-4">
                <div class="col-lg-8 col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Detail Produk</h5>
                        </div>
                        <div class="card-body">
                            @if (isset($carts) && $carts->count() > 0)
                                @foreach ($carts as $cart)
                                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                        <div class="chk-product-image me-3 flex-shrink-0">
                                            <img src="{{ asset('storage/' . ($cart->product->images->first()->image_path ?? 'images/default.jpg')) }}"
                                                alt="{{ $cart->product->name }}" class="img-fluid rounded"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                        <div class="chk-product-info flex-grow-1">
                                            <h5 class="chk-product-name mb-1">{{ $cart->product->name }}</h5>
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
                                            <h6 class="chk-product-price">
                                                Rp{{ number_format($cart->product->price, 2) }}
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif(isset($product))
                                <div class="d-flex align-items-start pb-3">
                                    <div class="chk-product-image me-3 flex-shrink-0">
                                        <img src="{{ asset('storage/' . ($product->images->first()->image_path ?? 'images/default.jpg')) }}"
                                            alt="{{ $product->name }}" class="img-fluid rounded"
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                    <div class="chk-product-info flex-grow-1">
                                        <h5 class="chk-product-name mb-1">{{ $product->name }}</h5>
                                        @if (isset($variant))
                                            <p class="text-muted mb-1">Color: {{ $variant->color ?? 'N/A' }}</p>
                                            <p class="text-muted mb-1">Size: {{ $variant->size ?? 'N/A' }}</p>
                                        @endif
                                        <p class="mb-1">Quantity: <strong class="text-dark">{{ $quantity }}</strong>
                                        </p>
                                        <h6 class="chk-product-price">
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
                    <form id="chk-form" action="{{ route('checkout.process') }}" method="POST">
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
                            <div class="card-header bg-secondary text-white">
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
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">Kode Voucher</h5>
                            </div>
                            <div class="card-body">
                                {{-- Select --}}
                                <div class="mb-2">
                                    <select id="voucher_select" class="form-select">
                                        <option value="">-- Pilih voucher tersedia --</option>
                                        @foreach ($vouchers as $voucherItem)
                                            <option value="{{ $voucherItem->code }}">
                                                {{ $voucherItem->code }} (
                                                @if ($voucherItem->type === 'percentage')
                                                    {{ $voucherItem->discount_value }}%
                                                @elseif ($voucherItem->type === 'fixed')
                                                    Rp{{ number_format($voucherItem->discount_value, 0, ',', '.') }}
                                                @endif
                                                )
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Input --}}
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
                            <div class="card-header bg-secondary text-white">
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
                                <h4 class="d-flex justify-content-between chk-product-total-price">Total Pembayaran:
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
    <script src="{{ asset('js/checkout.js') }}"></script>

@endsection
