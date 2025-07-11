@extends('layouts.index')

@section('title', 'Edit Profile')

@section('dongol')
    <div class="prfladmin-wrapper">
        <div class="prfladmin-container">
            <div class="prfladmin-header">
                <div class="prfladmin-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h2>Profile Settings</h2>
                <p>Manage your account information</p>
            </div>

            <div class="prfladmin-content">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="prfladmin-form">
                    @csrf
                    @method('PUT')

                    <div class="prfladmin-group-wrapper">
                        <div class="prfladmin-input-group">
                            <div class="prfladmin-input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="prfladmin-input-field">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                    placeholder="Enter your full name" required>
                            </div>
                        </div>

                        <div class="prfladmin-input-group">
                            <div class="prfladmin-input-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="prfladmin-input-field">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                    placeholder="Enter your email address" required>
                            </div>
                        </div>

                        <div class="prfladmin-input-group">
                            <div class="prfladmin-input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="prfladmin-input-field">
                                <label for="password">New Password</label>
                                <div class="prfladmin-password-wrapper">
                                    <input type="password" id="password" name="password"
                                        placeholder="Leave blank to keep current password">
                                    <button type="button" class="prfladmin-password-toggle"
                                        onclick="togglePasswordVisibility('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="prfladmin-input-group">
                            <div class="prfladmin-input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="prfladmin-input-field">
                                <label for="password_confirmation">Confirm Password</label>
                                <div class="prfladmin-password-wrapper">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        placeholder="Confirm your new password">
                                    <button type="button" class="prfladmin-password-toggle"
                                        onclick="togglePasswordVisibility('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="prfladmin-form-actions">
                        <button type="submit" class="prfladmin-btn prfladmin-btn-primary">
                            <i class="fas fa-save"></i>
                            <span>Save Changes</span>
                        </button>
                        <a href="{{ url('/dashboard') }}" class="prfladmin-btn prfladmin-btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to Dashboard</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        const toggleButton = field.nextElementSibling.querySelector('i');

        if (field.type === 'password') {
            field.type = 'text';
            toggleButton.classList.remove('fa-eye');
            toggleButton.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            toggleButton.classList.remove('fa-eye-slash');
            toggleButton.classList.add('fa-eye');
        }
    }

    // Add smooth focus transitions
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    });
</script>
