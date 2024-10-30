@extends('layouts.index')

@section('content')

<div class="container mt-5">
    <h1 class="text-center mb-4">Buat Brand Baru</h1>

    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Nama Brand:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi:</label>
            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="logo">Logo:</label>
            <input type="file" class="form-control-file" id="logo" name="logo" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Simpan</button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('brands.index') }}" class="btn btn-secondary">Kembali ke Daftar Brand</a>
    </div>
</div>

{{-- <h1>Buat Brand Baru</h1>
<form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Nama:</label>
    <input type="text" name="name" required>

    <label>Deskripsi:</label>
    <textarea name="description"></textarea>

    <label>Logo:</label>
    <input type="file" name="logo">

    <button type="submit">Buat</button>
</form> --}}

@endsection
