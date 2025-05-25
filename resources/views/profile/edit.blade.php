@extends('layouts.index')

@section('title', 'Edit Profile')

@section('content')
    <div class="profile-edit-container">
        <div>
        @include('components.profile-sidebar')
        </div>
        <div class="profile-edit-card">
            <div class="profile-edit-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Profile Picture Section -->
                    <div class="profile-picture-section">
                        <div class="profile-picture-wrapper">
                            @if (optional($user->profile)->profile_picture)
                                <img id="profile_picture_preview"
                                    src="{{ Storage::url('profile_pictures/' . $user->profile->profile_picture) }}"
                                    alt="Profile Picture" class="image-preview"
                                    onclick="document.getElementById('profile_picture').click();">
                            @else
                                <div class="default-avatar" id="profile_picture_preview"
                                    onclick="document.getElementById('profile_picture').click();">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                                onchange="previewImage(event)" style="display: none;">
                            <div class="upload-hint">
                                <i class="fas fa-camera"></i> Click to change photo
                            </div>
                        </div>
                    </div>

                    <!-- Main Form Section -->
                    <div class="profile-form-grid">
                        <!-- Personal Info Section -->
                        <div class="form-section personal-info">
                            <h4 class="section-title"><i class="fas fa-user"></i> Personal Information</h4>

                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required readonly
                                        style="background-color: #f8f9fa; cursor: not-allowed">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-phone"></i>
                                    <input type="text" id="phone_number" name="phone_number"
                                        value="{{ old('phone_number', $user->phone_number) }}">
                                </div>
                            </div>

                            <!-- Date of Birth Section -->
                            <div class="form-row">
                                <div class="form-group half-width">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-calendar"></i>
                                        <input type="text" id="date_of_birth" name="date_of_birth" class="date-picker"
                                            value="{{ old('date_of_birth', optional($user->profile)->date_of_birth) }}"
                                            readonly="readonly" placeholder="Select date">
                                    </div>
                                </div>
                            </div>

                            <!-- Gender Section -->
                            <div class="form-row">
                                <div class="form-group half-width">
                                    <label for="gender">Gender</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-venus-mars"></i>
                                        <select name="gender" id="gender">
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="male"
                                                {{ old('gender', optional($user->profile)->gender) == 'male' ? 'selected' : '' }}>
                                                Male
                                            </option>
                                            <option value="female"
                                                {{ old('gender', optional($user->profile)->gender) == 'female' ? 'selected' : '' }}>
                                                Female
                                            </option>
                                            <option value="other"
                                                {{ old('gender', optional($user->profile)->gender) == 'other' ? 'selected' : '' }}>
                                                Other
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password and Address Section -->
                        <div class="form-section password-address-section">
                            <!-- Password Change Section -->
                            <div class="password-change-section">
                                <h4 class="section-title"><i class="fas fa-lock"></i> Password Change</h4>

                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-key"></i>
                                        <input type="password" id="current_password" name="current_password"
                                            placeholder="Enter your current password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-lock"></i>
                                        <input type="password" id="new_password" name="new_password"
                                            placeholder="Enter new password">
                                        <div class="password-strength"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="new_password_confirmation">Confirm New Password</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-lock"></i>
                                        <input type="password" id="new_password_confirmation"
                                            name="new_password_confirmation" placeholder="Confirm your new password">
                                    </div>
                                </div>
                            </div>

                            <!-- Address Section -->
                            <div class="address-info">
                                <h4 class="section-title"><i class="fas fa-map-marker-alt"></i> Address Information</h4>

                                <div class="form-group">
                                    <label for="address_id">Primary Address</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-home"></i>
                                        <select name="address_id" id="address_id">
                                            <option value="" disabled selected>Select Address</option>
                                            @foreach ($addresses as $address)
                                                <option value="{{ $address->id }}"
                                                    {{ old('address_id', $user->profile->address_id) == $address->id ? 'selected' : '' }}>
                                                    {{ $address->address_line1 }}, {{ $address->city }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <a href="{{ route('profile.address.add') }}" class="add-address-btn">
                                        <i class="fas fa-plus"></i> Add New Address
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ url('/dashboard') }}" class="cancel-btn">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="save-btn">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('profile_picture_preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onloadend = function() {
                    if (preview.tagName === 'IMG') {
                        preview.src = reader.result;
                    } else {
                        const newImg = document.createElement('img');
                        newImg.id = 'profile_picture_preview';
                        newImg.className = 'image-preview';
                        newImg.src = reader.result;
                        newImg.alt = 'Profile Picture';
                        newImg.onclick = function() {
                            document.getElementById('profile_picture').click();
                        };
                        preview.parentNode.replaceChild(newImg, preview);
                    }
                }
                reader.readAsDataURL(file);
            }
        }

        // Password strength indicator
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const strengthIndicator = document.querySelector('.password-strength');
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthIndicator.className = 'password-strength strength-' + strength;
        });

        // Date Picker Configuration
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('#date_of_birth', {
                dateFormat: 'Y-m-d',
                minDate: '1900-01-01',
                maxDate: 'today',
                disableMobile: true,
                allowInput: false,
                clickOpens: true,
                onOpen: function() {
                    this.set('defaultDate', this.input.value);
                }
            });

            document.querySelector('#date_of_birth').style.cursor = 'pointer';
        });
    </script>
@endsection
