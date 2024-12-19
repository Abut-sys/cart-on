@extends('layouts.index')

@section('title', 'Edit Profile')

@section('content')
    <div class="profile-edit-card-container">
        <div class="profile-edit-card">
            <div class="profile-edit-header">
                <h3>Update Profile</h3>
            </div>
            <div class="profile-edit-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="profile-edit-fields">
                        <div class="profile-edit-field">
                            <label for="name">
                                <i class="fas fa-user"></i> Name
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Enter your full name"
                                required>
                        </div>

                        <div class="profile-edit-field">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                placeholder="Enter your email address"
                                required>
                        </div>

                        <div class="profile-edit-field">
                            <label for="password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div class="password-field">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Leave blank to keep current password">
                                <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password')"></i>
                            </div>
                        </div>

                        <div class="profile-edit-field">
                            <label for="password_confirmation">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <div class="password-field">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirm your new password">
                                <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password_confirmation')"></i>
                            </div>
                        </div>
                    </div>

                    <div class="profile-edit-actions">
                        <button type="submit" class="profile-edit-btn save">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="{{ url('/dashboard') }}" class="profile-edit-btn cancel">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<style>
    .profile-edit-card-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f8f9fa;
        padding: 20px;
    }

    .profile-edit-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
        padding: 20px 30px;
    }

    .profile-edit-header h3 {
        font-size: 1.5rem;
        color: #343a40;
        margin-bottom: 10px;
    }

    .profile-edit-header p {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .profile-edit-fields {
        margin-bottom: 20px;
    }

    .profile-edit-field {
        margin-bottom: 15px;
    }

    .profile-edit-field label {
        display: block;
        font-weight: bold;
        color: #495057;
        margin-bottom: 5px;
    }

    .profile-edit-field input {
        width: 100%;
        padding: 10px;
        border: 1px solid #7aa37a;
        border-radius: 5px;
        font-size: 1rem;
        color: #495057;
        outline: none;
        transition: border-color 0.3s;
    }

    .profile-edit-field input:focus {
        border-color: #7aa37a;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    .password-field {
        display: flex;
        align-items: center;
    }

    .password-field input {
        flex: 1;
    }

    .password-field .toggle-password {
        margin-left: -30px;
        cursor: pointer;
        color: #7aa37a;
    }

    .password-field .toggle-password:hover {
        color: #0056b3;
    }

    .profile-edit-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .profile-edit-btn {
        padding: 10px 20px;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .profile-edit-btn.save {
        background-color: #7aa37a;
        color: #ffffff;
    }

    .profile-edit-btn.save:hover {
        background-color: #7aa37a;
        transform: scale(1.05);
    }

    .profile-edit-btn.cancel {
        background-color: #6c757d;
        color: #ffffff;
    }

    .profile-edit-btn.cancel:hover {
        background-color: #5a6268;
        transform: scale(1.05);
    }
</style>

<script>
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        if (field.type === 'password') {
            field.type = 'text';
        } else {
            field.type = 'password';
        }
    }
</script>
