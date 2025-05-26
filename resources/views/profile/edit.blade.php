@extends('layouts.index')

@section('title', 'Edit Profile')

@section('content')
    <div class="profile-edit-container">
        <div>
            @include('components.profile-sidebar')
        </div>
        <div class="profile-edit-card">
            <!-- Filter Tabs -->
            <div class="profile-tabs">
                <button class="tab-btn active" data-tab="personal-info">
                    <i class="fas fa-user"></i> Personal Info
                </button>
                <button class="tab-btn" data-tab="password-change">
                    <i class="fas fa-lock"></i> Password
                </button>
                <button class="tab-btn" data-tab="address-info">
                    <i class="fas fa-map-marker-alt"></i> Address
                </button>
            </div>

            <div class="profile-edit-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="profile-content-wrapper">
                        <!-- Profile Picture Section (Left Side) -->
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

                        <!-- Form Content Section (Right Side) -->
                        <div class="form-content-section">
                            <!-- Tab Content Sections -->
                            <div class="tab-content">
                                <!-- Personal Info Tab -->
                                <div id="personal-info" class="tab-pane active">
                                    <h4 class="section-title"><i class="fas fa-user"></i> Personal Information</h4>

                                    <div class="form-section">
                                        <div class="form-row">
                                            <div class="form-group half-width">
                                                <label for="name">Full Name</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-user"></i>
                                                    <input type="text" id="name" name="name"
                                                        value="{{ old('name', $user->name) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group half-width">
                                                <label for="email">Email Address</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-envelope"></i>
                                                    <input type="email" id="email" name="email"
                                                        value="{{ old('email', $user->email) }}" required readonly
                                                        style="background-color: #f8f9fa; cursor: not-allowed">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group half-width">
                                                <label for="phone_number">Phone Number</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-phone"></i>
                                                    <input type="text" id="phone_number" name="phone_number"
                                                        value="{{ old('phone_number', $user->phone_number ?? '+62') }}" pattern="\+62\d*"
                                                        maxlength="15" oninput="formatPhoneNumber(this)">
                                                </div>
                                            </div>

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

                                        <div class="form-group">
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

                                <!-- Password Change Tab -->
                                <div id="password-change" class="tab-pane">
                                    <div class="form-section">
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
                                </div>

                                <!-- Address Info Tab -->
                                <div id="address-info" class="tab-pane">
                                    <div class="form-section">
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

                            <!-- Form Actions (Visible in all tabs) -->
                            <div class="form-actions">
                                <a href="{{ url('/dashboard') }}" class="cancel-btn">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="save-btn">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons and panes
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabPanes.forEach(p => p.classList.remove('active'));

                    // Add active class to clicked button and corresponding pane
                    this.classList.add('active');
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // Initialize first tab as active if none is active
            if (document.querySelectorAll('.tab-pane.active').length === 0) {
                tabPanes[0].classList.add('active');
            }
        });

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
        document.getElementById('new_password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthIndicator = document.querySelector('.password-strength');
            if (!strengthIndicator) return;

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

        function formatPhoneNumber(input) {
            // Hilangkan semua karakter non-digit
            let value = input.value.replace(/[^\d]/g, '');

            // Pastikan selalu diawali dengan 62
            if (!value.startsWith('62')) {
                value = '62' + value;
            }

            // Batasi panjang maksimal 15 karakter (62 + 13 digit)
            value = value.substring(0, 15);

            // Tambahkan tanda + di depan
            input.value = '+' + value;

            // Jika user menghapus tanda +, tambahkan kembali
            if (!input.value.startsWith('+')) {
                input.value = '+' + input.value;
            }

            // Posisikan kursor di akhir
            const length = input.value.length;
            input.setSelectionRange(length, length);
        }

        // Validasi saat form disubmit
        document.querySelector('form').addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('phone_number');
            if (phoneInput) {
                const phoneNumber = phoneInput.value;

                // Validasi panjang minimum
                if (phoneNumber.length < 4) { // +62 minimal
                    e.preventDefault();
                    alert('Nomor telepon harus minimal 12 digit termasuk +62');
                    phoneInput.focus();
                }

                // Validasi format
                if (!/^\+62\d{10,12}$/.test(phoneNumber)) {
                    e.preventDefault();
                    alert('Format nomor telepon tidak valid');
                    phoneInput.focus();
                }
            }
        });
    </script>
@endsection
