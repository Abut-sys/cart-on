@extends('layouts.index')

@section('content')

<div class="container mt-4">
    <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;">
        <!-- Light gray background -->
        <div class="card-header d-flex justify-content-between" style="background-color: #d3d3d3;">
            <!-- Light gray header -->
            <h2 class="mb-0" style="color: black;">Add New Brand</h2>
            <!-- Black header text -->
            <a href="{{ route('brands.index') }}" class="btn btn-danger" style="background-color: #ff0000; color: black;">
                <i class="fas fa-arrow-left"></i> Return To Brand List
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name" style="font-weight: bold; color: black; margin-bottom: 5px;">Brand Name</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Nama Brand" style="background-color: #dcdcdc; border-color: #c0c0c0;">
                </div>

                <div class="form-group mt-3">
                    <label for="description" style="font-weight: bold; color: black; margin-bottom: 5px;">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Deskripsi Brand" style="background-color: #dcdcdc; border-color: #c0c0c0;"></textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="logo" style="font-weight: bold; color: black; margin-bottom: 5px;">Logo</label>
                    <input type="file" class="form-control-file" id="logo" name="logo" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn confirm-btn" style="background-color: #00ff00; color: black;">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- <div class="container mt-5">
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
</div> --}}

@endsection
