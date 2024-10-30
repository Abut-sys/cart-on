@extends('layouts.index')

@section('content')

<div class="container mt-5">
    <h1 class="text-center mb-4">Edit Brand</h1>

    <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Brand:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $brand->name }}" required>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi:</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ $brand->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="logo">Logo:</label>
            <input type="file" class="form-control-file" id="logo" name="logo">
            @if ($brand->logo_path)
                <small class="form-text text-muted">Logo saat ini:</small>
                <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" width="100" class="img-thumbnail">
            @endif
        </div>

        <button type="submit" class="btn btn-primary btn-block">Perbarui</button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('brands.index') }}" class="btn btn-secondary">Kembali ke Daftar Brand</a>
    </div>
</div>

{{-- <h1>Edit Brand</h1>
<form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <label>Nama:</label>
    <input type="text" name="name" value="{{ $brand->name }}" required>

    <label>Deskripsi:</label>
    <textarea name="description">{{ $brand->description }}</textarea>

    <label>Logo:</label>
    <input type="file" name="logo">
    <button type="submit">Perbarui</button>
</form> --}}

@endsection
