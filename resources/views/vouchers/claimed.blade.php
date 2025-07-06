@extends('layouts.index')

@section('content')
    <div class="container mt-5 voucher-claimed-container">
        <h1 class="text-center mb-4 voucher-claimed-title">Your Claimed Vouchers</h1>

        <div class="text-center mb-4 voucher-claimed-link">
            <a href="{{ route('voucher.claim') }}"
                class="voucher-claimed-btn voucher-claimed-btn-info voucher-claimed-btn-lg voucher-claimed-shadow-sm">
                <i class="fas fa-ticket-alt"></i> Go to Claim Vouchers
            </a>
        </div>

        @if ($claimedVouchers->isEmpty())
            <div class="voucher-claimed-alert alert alert-warning text-center">
                You haven't claimed any vouchers yet.
            </div>
        @else
            <div class="voucher-claimed-row d-flex flex-wrap">
                @foreach ($claimedVouchers as $claimedVoucher)
                    @php
                        $voucher = $claimedVoucher->voucher;
                        $quantity = $claimedVoucher->quantity ?? 1;
                        $slotsLeft = $voucher->max_per_user - $quantity;

                        $discountLabel =
                            $voucher->type === 'percentage'
                                ? $voucher->discount_value . '% OFF'
                                : 'Discount Rp ' . number_format($voucher->discount_value, 0, ',', '.');
                    @endphp

                    <div class="voucher-claimed-col">
                        <div class="voucher-claimed-card card shadow-lg border-0 rounded-lg position-relative">
                            <div class="voucher-claimed-card-body card-body">
                                <h5 class="voucher-claimed-code card-title text-center font-weight-bold mb-3">
                                    {{ $voucher->code }}
                                </h5>

                                <p class="voucher-claimed-validity card-text text-center mb-2">
                                    <span class="text-muted">Claimed On:</span>
                                    <strong>{{ $claimedVoucher->created_at->format('M d, Y') }}</strong>
                                </p>

                                <p class="voucher-claimed-validity card-text text-center mb-2">
                                    <span class="text-muted">Validity:</span>
                                    <strong>{{ \Carbon\Carbon::parse($voucher->start_date)->format('M d, Y') }}</strong>
                                    <span> to </span>
                                    <strong>{{ \Carbon\Carbon::parse($voucher->end_date)->format('M d, Y') }}</strong>
                                </p>

                                <p class="voucher-claimed-quantity card-text text-center mb-2">
                                    <strong>Claimed:</strong> {{ $quantity }} / {{ $voucher->max_per_user }}
                                </p>

                                <div
                                    class="voucher-claimed-discount position-absolute top-0 end-0 bg-danger text-white px-3 py-2 rounded-bottom-start">
                                    {{ $discountLabel }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
