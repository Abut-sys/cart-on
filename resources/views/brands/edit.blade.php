@extends('layouts.index')

@section('title', 'Brands')

@section('content')

<div class="container mt-4">
    <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d3d3d3;">
            <!-- Light gray header -->
            <h2 class="mb-0" style="color: black;">Edit Brand</h2> <!-- Black header text -->
            <a href="{{ route('brands.index') }}" class="btn btn-danger me-2" style="background-color: #ff0000; color: black;">
                <i class="fas fa-arrow-left"></i> Return
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                    <label for="name" style="font-weight: bold; color: black; margin-bottom: 5px;">Brand Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $brand->name }}" required
                        style="background-color: #dcdcdc; border-color: #c0c0c0;"> <!-- Gray input -->
                </div>

                <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                    <label for="description" style="font-weight: bold; color: black; margin-bottom: 5px;">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4"
                        style="background-color: #dcdcdc; border-color: #c0c0c0;">{{ $brand->description }}</textarea>
                </div>

                <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                    <label for="logo" style="font-weight: bold; color: black; margin-bottom: 5px;">Logo:</label>
                    <input type="file" class="form-control-file" id="logo" name="logo">
                    @if ($brand->logo_path)
                        <small class="form-text text-muted">Logo saat ini:</small>
                        <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" width="100" class="img-thumbnail">
                    @endif
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn" style="background-color: #00ff00; color: black;">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- <div class="container mt-5">
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
</div> --}}

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
