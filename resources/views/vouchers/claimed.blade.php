@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Your Claimed Vouchers</h1>

        <!-- Link to go back to Claim Voucher page -->
        <div class="text-center mb-4">
            <a href="{{ route('voucher.claim') }}" class="btn btn-info btn-lg shadow-sm custom-hover">Go to Claim Vouchers</a>
        </div>

        @if($claimedVouchers->isEmpty())
            <div class="alert alert-warning text-center">
                You haven't claimed any vouchers yet.
            </div>
        @else
            <div class="row">
                @foreach($claimedVouchers as $claimedVoucher)
                    <div class="col-md-4 mb-4">
                        <div class="card voucher-card shadow-lg border-0 rounded-lg">
                            <div class="card-body">
                                <h5 class="card-title text-center text-primary font-weight-bold mb-3">
                                    {{ $claimedVoucher->voucher->code }} ({{ $claimedVoucher->voucher->discount_value }}% off)
                                </h5>

                                <p class="card-text text-center">
                                    <strong class="text-muted">Claimed On:</strong>
                                    {{ $claimedVoucher->created_at->format('M d, Y') }}
                                </p>
                                <p class="card-text text-center">
                                    <strong class="text-muted">Validity:</strong>
                                    {{ \Carbon\Carbon::parse($claimedVoucher->voucher->start_date)->format('M d, Y') }} to
                                    {{ \Carbon\Carbon::parse($claimedVoucher->voucher->end_date)->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
