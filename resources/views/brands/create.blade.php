@extends('layouts.index')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-sm brand-card">
        <div class="card-header d-flex justify-content-between align-items-center brand-card-header">
            <h2 class="mb-0">Add New Brand</h2>
            <a href="{{ route('brands.index') }}" class="btn brand-btn-return me-2">
                <i class="fas fa-arrow-left"></i> Return To Brand List
            </a>
        </div>

        <div class="card-body brand-card-body">
            <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name" class="brand-label">Brand Name</label>
                    <input type="text" class="form-control brand-input" id="name" name="name" required placeholder="Nama Brand">
                </div>

                <div class="form-group mt-3">
                    <label for="description" class="brand-label">Description</label>
                    <textarea class="form-control brand-input" id="description" name="description" rows="4" placeholder="Deskripsi Brand"></textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="logo" class="brand-label">Logo</label>
                    <input type="file" class="form-control-file" id="logo" name="logo" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn brand-btn-save">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
