@extends('layouts.index')

@section('title', 'Edit Profile')

@section('content')
    <div class="profile-edit-card-container">
        <div class="profile-edit-card">
            <div class="profile-edit-header">
                <h3>Update Profile</h3>
                <p>Update Your Profile in here</p>
            </div>
            <div class="profile-edit-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="profile-edit-fields">
                        <div class="profile-edit-field horizontal">
                            <label for="profile_picture" class="d-flex align-items-center">
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
                                        <div class="default-avatar" id="profile_picture_preview">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                                        onchange="previewImage(event)" style="display: none;">
                                </div>

                                <span class="ml-3">
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required
                                        style="border: none; background: none; font-size: 16px; font-weight: bold;">
                                </span>
                            </label>
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                required>
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="phone_number">
                                <i class="fas fa-phone"></i> Phone
                            </label>
                            <input type="text" id="phone_number" name="phone_number"
                                value="{{ old('phone_number', $user->phone_number) }}">
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="date_of_birth">
                                <i class="fas fa-calendar"></i> Date of Birth
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth', optional($user->profile)->date_of_birth) }}">
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="gender">
                                <i class="fas fa-venus-mars"></i> Gender
                            </label>
                            <select name="gender" id="gender">
                                <option value="" disabled selected>Select Gender</option>
                                <option value="male"
                                    {{ old('gender', optional($user->profile)->gender) == 'male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="female"
                                    {{ old('gender', optional($user->profile)->gender) == 'female' ? 'selected' : '' }}>
                                    Female</option>
                                <option value="other"
                                    {{ old('gender', optional($user->profile)->gender) == 'other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="address_id">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </label>
                            <select name="address_id" id="address_id">
                                <option value="" disabled selected>Select Address</option>
                                @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}"
                                        {{ old('address_id', $user->profile->address_id) == $address->id ? 'selected' : '' }}>
                                        {{ $address->address_line1 }}, {{ $address->city }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="profile-edit-actions mt-2">
                                <a href="{{ route('profile.address.add') }}" class="profile-edit-btn secondary">Add New
                                    Address</a>
                            </div>
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" id="password" name="password">
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="password_confirmation">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="profile-edit-actions">
                        <button type="submit" class="profile-edit-btn primary">Save</button>
                        <a href="{{ url('/dashboard') }}" class="profile-edit-btn secondary">Cancel</a>
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
                preview.src = URL.createObjectURL(file);
            }
        }
    </script>

    <style>
        .image-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid #99bc85;
            object-fit: cover;
        }

        .profile-picture-wrapper {
            position: relative;
            display: inline-block;
            width: 80px;
            height: 80px;
        }

        .image-preview {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid #99bc85;
            object-fit: cover;
            display: block;
        }

        .default-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #99bc85;
            color: white;
            font-weight: bold;
            font-size: 24px;
            text-transform: uppercase;
            text-align: center;
        }


        .profile-picture-wrapper input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .profile-edit-field label span {
            font-weight: bold;
            font-size: 16px;
            margin-left: 10px;
        }
    </style>
@endsection
