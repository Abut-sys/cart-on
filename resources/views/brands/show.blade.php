@extends('layouts.index')

@section('content')

<div class="container mt-5">
    <h1 class="text-center">{{ $brand->name }}</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Deskripsi:</h5>
            <p class="card-text">{{ $brand->description }}</p>

            @if ($brand->logo_path)
                <h5 class="card-title">Logo:</h5>
                <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" class="img-fluid" width="100">
            @endif

            <div class="mt-4">
                <a href="{{ route('brands.index') }}" class="btn btn-secondary">Kembali ke Daftar Brand</a>
            </div>
        </div>
    </div>
</div>


{{-- <h1>{{ $brand->name }}</h1>
<p>Deskripsi: {{ $brand->description }}</p>
<img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" width="100">
<a href="{{ route('brands.index') }}">Kembali ke Daftar Brand</a> --}}

@endsection
