@extends('layouts.index')

@section('content')

<h1 class="text-center my-4">Daftar Brand</h1>
<div class="text-center mb-3">
    <a href="{{ route('brands.create') }}" class="btn btn-primary">Buat Brand Baru</a>
</div>

@if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <ul class="list-group">
        @foreach ($brands as $brand)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" width="50" class="mr-3">
                    <div>
                        <h5 class="mb-1">{{ $brand->name }}</h5>
                    </div>
                </div>
                <div>
                    <a href="{{ route('brands.show', $brand->id) }}" class="btn btn-info btn-sm">Lihat</a>
                    <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>

@endsection
