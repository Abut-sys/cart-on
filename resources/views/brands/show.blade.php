@extends('layouts.index')

@section('title', 'Details Brands')

@section('content')

<div class="brand-show-container-fluid mt-4">
    <div class="brand-show-card mb-4 shadow-sm">
        <div class="brand-show-card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0 brand-show-title">{{ $brand->name }}</h2>
            <a href="{{ route('brands.index') }}" class="brand-show-btn-return me-2">
                <i class="fas fa-arrow-left"></i> Return
            </a>
        </div>
        <div class="brand-show-card-body">
            <h5 class="card-title brand-show-description-title">Description:</h5>
            <p class="card-text brand-show-description-text">{{ $brand->description }}</p>

            @if ($brand->logo_path)
                <h5 class="card-title brand-show-logo-title">Logo:</h5>
                <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" class="brand-show-img-fluid">
            @endif

            <div class="mt-4">
                <a href="{{ route('brands.index') }}" class="brand-show-btn-back">
                    Kembali ke Daftar Brand
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
