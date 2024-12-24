@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Claim Your Voucher</h1>

        <!-- Link to navigate to Your Vouchers page -->
        <div class="text-center mb-4">
            <a href="{{ route('your-vouchers') }}" class="btn btn-info btn-lg shadow-sm custom-hover">
                <i class="fas fa-ticket-alt"></i> View Your Claimed Vouchers
            </a>
        </div>

        @if ($vouchers->isEmpty())
            <div class="alert alert-warning text-center">
                No vouchers available for claiming at the moment.
            </div>
        @else
            <div class="row">
                @foreach ($vouchers as $voucher)
                    <div class="col-md-4 mb-4">
                        <div class="card voucher-card shadow-lg border-0 rounded-lg"> <!-- Changed class name here -->
                            <div class="card-body">
                                <h5 class="card-title text-center text-primary font-weight-bold mb-3">
                                    {{ $voucher->code }} ({{ $voucher->discount_value }}% off)
                                </h5>
                                <p class="card-text text-center">
                                    <strong class="text-muted">Validity:</strong>
                                    {{ \Carbon\Carbon::parse($voucher->start_date)->format('M d, Y') }} to
                                    {{ \Carbon\Carbon::parse($voucher->end_date)->format('M d, Y') }}
                                </p>
                                <p class="card-text text-center">
                                    <strong class="text-muted">Terms:</strong>
                                    {{ $voucher->terms_and_conditions ?? 'No specific terms.' }}
                                </p>

                                <form action="{{ route('claim', $voucher->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-success w-100 py-3 font-weight-bold mt-4 shadow-sm hover-zoom-voucher"> <!-- Updated hover class -->
                                        Claim Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
