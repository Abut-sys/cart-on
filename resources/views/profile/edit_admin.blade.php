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

<style>
    .prfladmin-wrapper {
        min-height: 100vh;
        background: #f5f5f5;
        padding: 2rem 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .prfladmin-container {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
        border: 1px solid #e0e0e0;
    }

    .prfladmin-header {
        background: #ffffff;
        color: #333;
        padding: 2rem;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }

    .prfladmin-icon {
        width: 60px;
        height: 60px;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border: 2px solid #e0e0e0;
    }

    .prfladmin-icon i {
        font-size: 1.5rem;
        color: #6c757d;
    }

    .prfladmin-header h2 {
        margin: 0 0 0.5rem;
        font-size: 1.5rem;
        font-weight: 500;
        color: #333;
    }

    .prfladmin-header p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .prfladmin-content {
        padding: 2rem;
    }

    .prfladmin-group-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .prfladmin-input-group {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .prfladmin-input-icon {
        width: 40px;
        height: 40px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1.5rem;
        border: 1px solid #e0e0e0;
    }

    .prfladmin-input-icon i {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .prfladmin-input-field {
        flex: 1;
    }

    .prfladmin-input-field label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #333;
        font-size: 0.9rem;
    }

    .prfladmin-input-field input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .prfladmin-input-field input:focus {
        outline: none;
        border-color: #999;
        box-shadow: 0 0 0 2px rgba(153, 153, 153, 0.1);
    }

    .prfladmin-password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .prfladmin-password-wrapper input {
        padding-right: 3rem;
    }

    .prfladmin-password-toggle {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        cursor: pointer;
        color: #6c757d;
        font-size: 0.9rem;
        padding: 0.5rem;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .prfladmin-password-toggle:hover {
        background: #f8f9fa;
        color: #495057;
    }

    .prfladmin-form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .prfladmin-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
        justify-content: center;
        min-width: 120px;
    }

    .prfladmin-btn-primary {
        background: #333;
        color: white;
        border-color: #333;
    }

    .prfladmin-btn-primary:hover {
        background: #555;
        border-color: #555;
    }

    .prfladmin-btn-secondary {
        background: #ffffff;
        color: #6c757d;
        border-color: #ddd;
    }

    .prfladmin-btn-secondary:hover {
        background: #f8f9fa;
        color: #495057;
        border-color: #bbb;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .prfladmin-wrapper {
            padding: 1rem;
        }

        .prfladmin-content {
            padding: 1.5rem;
        }

        .prfladmin-input-group {
            flex-direction: column;
            gap: 0.5rem;
        }

        .prfladmin-input-icon {
            align-self: flex-start;
            margin-top: 0;
        }

        .prfladmin-form-actions {
            flex-direction: column;
        }

        .prfladmin-btn {
            flex: none;
        }
    }

    /* Focus states */
    .prfladmin-btn:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(153, 153, 153, 0.3);
    }

    .prfladmin-password-toggle:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(153, 153, 153, 0.2);
    }
</style>

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
