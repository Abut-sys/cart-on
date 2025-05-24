@extends('layouts.index')

@section('content')
    <div class="container mt-5 voucher-claimed-container">
        <h1 class="text-center mb-4 voucher-claimed-title">Your Claimed Vouchers</h1>

        <!-- Link to navigate back to Claim Vouchers page -->
        <div class="text-center mb-4 voucher-claimed-link">
            <a href="{{ route('voucher.claim') }}" class="voucher-claimed-btn voucher-claimed-btn-info voucher-claimed-btn-lg voucher-claimed-shadow-sm">
                <i class="fas fa-ticket-alt"></i> Go to Claim Vouchers
            </a>
        </div>

        @if ($claimedVouchers->isEmpty())
            <div class="voucher-claimed-alert alert alert-warning text-center">
                You haven't claimed any vouchers yet.
            </div>
        @else
            <div class="voucher-claimed-row row">
                @foreach ($claimedVouchers as $claimedVoucher)
                    <div class="voucher-claimed-col col-md-4 mb-4">
                        <div class="voucher-claimed-card card shadow-lg border-0 rounded-lg">
                            <div class="voucher-claimed-card-body card-body">
                                <h5 class="voucher-claimed-code card-title text-center font-weight-bold mb-3">
                                    {{ $claimedVoucher->voucher->code }}
                                </h5>

                                <p class="voucher-claimed-validity card-text text-center">
                                    <span class="text-muted">Claimed On:</span>
                                    <strong>{{ $claimedVoucher->created_at->format('M d, Y') }}</strong>
                                </p>

                                <p class="voucher-claimed-validity card-text text-center">
                                    <span class="text-muted">Validity:</span>
                                    <strong>{{ \Carbon\Carbon::parse($claimedVoucher->voucher->start_date)->format('M d, Y') }}</strong>
                                    <span> to </span>
                                    <strong>{{ \Carbon\Carbon::parse($claimedVoucher->voucher->end_date)->format('M d, Y') }}</strong>
                                </p>

                                <!-- Label Diskon -->
                                <div class="voucher-claimed-discount">
                                    {{ $claimedVoucher->voucher->discount_value }}% OFF
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
