@extends('layouts.index')

@section('title', 'Customer')

@section('content')
    <div class="create-costumers-container mt-5">
        <div class="create-costumers-card shadow-lg">
            <div class="create-costumers-card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Add User</h2>
                <a href="{{ route('costumers.index') }}" class="create-costumers-btn create-costumers-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="create-costumers-card-body">
                <form action="{{ route('costumers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="create-costumers-form-group mb-4">
                        <label for="name" class="create-costumers-form-label">Name</label>
                        <input type="text" name="name" id="name" class="create-costumers-form-control" required>
                    </div>

                    <div class="create-costumers-form-group mb-4">
                        <label for="email" class="create-costumers-form-label">Email</label>
                        <input type="email" name="email" id="email" class="create-costumers-form-control" required>
                    </div>

                    <div class="create-costumers-form-group mb-4">
                        <label for="phone_number" class="create-costumers-form-label">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="create-costumers-form-control"
                            required>
                    </div>

                    <div class="create-costumers-form-group mb-4">
                        <label for="role" class="create-costumers-form-label">Role</label>
                        <select name="role" id="role" class="create-costumers-form-control">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="create-costumers-form-group mb-4">
                        <label for="password" class="create-costumers-form-label">Password</label>
                        <input type="password" name="password" id="password" class="create-costumers-form-control"
                            required>
                    </div>

                    <div class="create-costumers-form-group mb-4">
                        <label for="password_confirmation" class="create-costumers-form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="create-costumers-form-control" required>
                    </div>

                    <div class="create-costumers-form-group mb-4">
                        <label for="image" class="create-costumers-form-label">User Image</label>
                        <input type="file" class="create-costumers-form-control-file" id="image" name="image"
                            accept="image/*" onchange="previewImage(event)">
                        <img id="image-preview" src="" alt="Image Preview" class="create-costumers-image-preview"
                            style="display:none;">
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            class="create-costumers-btn create-costumers-btn-success w-100">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/imagePreview.js') }}"></script>
@endsection
