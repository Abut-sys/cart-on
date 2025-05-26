@extends('layouts.index')

@section('title', 'Edit Profile')

@section('content')
    <div class="profile-edit-card-container">
        <div class="profile-edit-card">
            <div class="profile-edit-header">
                <h3>Update Profile</h3>
            </div>
            <div class="profile-edit-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="profile-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="profile-edit-fields">
                        <div class="profile-edit-field">
                            <label for="name">
                                <i class="fas fa-user"></i> Name
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                placeholder="Enter your full name" required>
                        </div>

                        <div class="profile-edit-field">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                placeholder="Enter your email address" required>
                        </div>

                        <div class="profile-edit-field">
                            <label for="password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div class="password-field">
                                <input type="password" id="password" name="password"
                                    placeholder="Leave blank to keep current password">
                                <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password')"></i>
                            </div>
                        </div>

                        <div class="profile-edit-field">
                            <label for="password_confirmation">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <div class="password-field">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Confirm your new password">
                                <i class="fas fa-eye toggle-password"
                                    onclick="togglePasswordVisibility('password_confirmation')"></i>
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
