@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4 voucher-claim-title">Claim Your Voucher</h1>

        <!-- Link to navigate to Your Vouchers page -->
        <div class="text-center mb-4">
            <a href="{{ route('your-vouchers') }}" class="voucher-claim-btn voucher-claim-btn-info voucher-claim-btn-lg voucher-claim-shadow-sm">
                <i class="fas fa-ticket-alt"></i> View Your Claimed Vouchers
            </a>
        </div>

        @if ($vouchers->isEmpty())
            <div class="voucher-claim-alert alert alert-warning text-center">
                No vouchers available for claiming at the moment.
            </div>
        @else
            <div class="voucher-claim-row row">
                @foreach ($vouchers as $voucher)
                    <div class="voucher-claim-col col-md-4 mb-4">
                        <div class="voucher-claim-card card shadow-lg border-0 rounded-lg">
                            <div class="voucher-claim-card-body card-body">
                                <h5 class="voucher-claim-code card-title text-center font-weight-bold mb-3">
                                    {{ $voucher->code }}
                                </h5>
                                
                            
                                <p class="voucher-claim-validity card-text text-center">
                                    <span class="text-muted">Valid From:</span> 
                                    <strong>{{ \Carbon\Carbon::parse($voucher->start_date)->format('M d, Y') }}</strong>
                                    <span> to </span>
                                    <strong>{{ \Carbon\Carbon::parse($voucher->end_date)->format('M d, Y') }}</strong>
                                </p>
                            
                                <p class="voucher-claim-terms card-text text-center">
                                    <span class="text-muted">Terms:</span> 
                                    <em>{{ $voucher->terms_and_conditions ?? 'No specific terms apply. Enjoy your savings!' }}</em>
                                </p>
                            
                                <form action="{{ route('claim', $voucher->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="voucher-claim-btn voucher-claim-btn-success w-100 py-3 font-weight-bold mt-4 voucher-claim-shadow-sm">
                                        Claim This Voucher
                                    </button>
                                </form>
                            
                                <!-- Label Diskon -->
                                <div class="voucher-claim-discount">
                                    {{ $voucher->discount_value }}% OFF
                                </div>
                            </div>
                            
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

