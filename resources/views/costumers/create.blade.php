@extends('layouts.index')

@section('content')
    <div class="create-costumers-container">
        <h1 class="create-costumers-title">Add User</h1>
        <form action="{{ route('costumers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="create-costumers-form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="create-costumers-form-control" required>
            </div>
            <div class="create-costumers-form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="create-costumers-form-control" required>
            </div>
            <div class="create-costumers-form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="create-costumers-form-control" required>
            </div>
            <div class="edit-user-form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="edit-user-form-control">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="create-costumers-form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="create-costumers-form-control" required>
            </div>
            <div class="create-costumers-form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="create-costumers-form-control" required>
            </div>
            <div class="create-costumers-form-group">
                <label for="image">User Image</label>
                <input type="file" class="create-costumers-form-control-file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                <img id="image-preview" src="" alt="Image Preview" class="create-costumers-image-preview" style="display:none;">
            </div>
            <div class="create-costumers-buttons">
                <button type="submit" class="create-costumers-btn create-costumers-btn-primary">Add</button>
                <a href="{{ route('costumers.index') }}" class="create-costumers-btn create-costumers-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('image-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
        }
    </script>
@endsection
