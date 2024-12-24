@extends('layouts.index')

@section('title', 'Edit Profile')

@section('content')
    <div class="profile-edit-card">
        <div class="profile-edit-header">
            <h3>Update Profile</h3>
        </div>
        <div class="profile-edit-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="profile-picture-wrapper">
                    @if (optional($user->profile)->profile_picture)
                        <img id="profile_picture_preview"
                            src="{{ old('profile_picture')
                                ? asset('storage/' . old('profile_picture'))
                                : (optional($user->profile)->profile_picture
                                    ? Storage::url('profile_pictures/' . $user->profile->profile_picture)
                                    : asset('images/default-profile.png')) }}"
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
                </div>

                <div class="profile-edit-top-section">
                    <div class="profile-edit-details">
                        <div class="profile-name-field">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="profile-edit-field">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="profile-edit-field">
                            <label for="phone_number">Phone</label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                        </div>

                        <div class="profile-edit-field">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="text" id="date_of_birth" name="date_of_birth" class="date-picker"
                                value="{{ old('date_of_birth', optional($user->profile)->date_of_birth) }}">
                        </div>

                        <div class="profile-edit-field">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender">
                                <option value="" disabled selected>Select Gender</option>
                                <option value="male"
                                    {{ old('gender', optional($user->profile)->gender) == 'male' ? 'selected' : '' }}>
                                    Male
                                </option>
                                <option value="female"
                                    {{ old('gender', optional($user->profile)->gender) == 'female' ? 'selected' : '' }}>
                                    Female</option>
                                <option value="other"
                                    {{ old('gender', optional($user->profile)->gender) == 'other' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>
                        </div>
                    </div>

                        <div class="profile-edit-field">
                            <label for="address_id">Address</label>
                            <select name="address_id" id="address_id">
                                <option value="" disabled selected>Select Address</option>
                                @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}"
                                        {{ old('address_id', $user->profile->address_id) == $address->id ? 'selected' : '' }}>
                                        {{ $address->address_line1 }}, {{ $address->address_line2 }},
                                        {{ $address->state }}, {{ $address->city }}, {{ $address->postal_code }},
                                        {{ $address->country }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="profile-edit-actions add">
                                <a href="{{ route('profile.address.add') }}" class="profile-edit-btn secondary">
                                    Add New Address
                                </a>
                            </div>
                        </div>

                        <div class="profile-edit-field">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password">
                        </div>

                        <div class="profile-edit-field">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                </div>

                <div class="profile-edit-fields">
                    <div class="profile-edit-actions">
                        <button type="submit" class="profile-edit-btn save">Save</button>
                        <a href="{{ url('/dashboard') }}" class="profile-edit-btn cancel">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview image function
        function previewImage(event) {
            const preview = document.getElementById('profile_picture_preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onloadend = function() {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                }

                if (file) {
                    reader.readAsDataURL(file);
                }
            } else {
                preview.src = "{{ asset('images/default-profile.png') }}";
                preview.style.display = 'block';
            }
        }

        // Initialize Flatpickr
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr('.date-picker', {
                dateFormat: 'Y-m-d',
                minDate: '1900-01-01',
                maxDate: 'today',
                disableMobile: true,
            });
        });
    </script>
@endsection
