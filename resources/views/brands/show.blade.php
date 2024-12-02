@extends('layouts.index')

@section('title', 'Details Brands')

@section('content')

<div class="brand-show-container mt-4">
    <div class="brand-show-card shadow-lg">
        <div class="brand-show-card-header d-flex justify-content-between align-items-center">
            <h2 class="brand-show-title mb-0">Brand Details</h2>
            <a href="{{ route('brands.index') }}" class="brand-show-btn-return">
                <i class="fas fa-arrow-left"></i> Return
            </a>
        </div>

        <div class="brand-show-card-body d-flex">
            @if ($brand->logo_path)
                <div class="brand-logo-container">
                    <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" class="brand-show-img-fluid">
                </div>
            @endif
        
            <div class="brand-details-container">
                <div class="brand-show-form-group">
                    <label for="name" class="brand-show-label">Brand Name:</label>
                    <p class="brand-show-text">{{ $brand->name }}</p>
                </div>
        
                <div class="brand-show-form-group">
                    <label for="description" class="brand-show-label">Description:</label>
                    <p class="brand-show-text">{{ $brand->description }}</p>
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection
