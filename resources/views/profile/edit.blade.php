@extends('layouts.index')

@section('title', 'Edit Profile')

@section('content')
    <div class="profile-edit-card-container">
        <div class="profile-edit-card">
            <div class="profile-edit-header">
                <h3>Update Profile</h3>
                <p>Update your profile here</p>
            </div>
            <div class="profile-edit-body">
                @if (session('msg'))
                    <div class="profile-edit-alert">
                        <strong>Success!</strong> {{ session('msg') }}
                    </div>
                @endif
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="profile-edit-fields">
                        <!-- Name Field -->
                        <div class="profile-edit-field">
                            <label for="name">
                                <i class="fas fa-user"></i> Name
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                required>
                        </div>

                        <!-- Email Field -->
                        <div class="profile-edit-field">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                required>
                        </div>

                        <!-- Phone Field -->
                        <div class="profile-edit-field">
                            <label for="phone_number">
                                <i class="fas fa-phone"></i> Phone
                            </label>
                            <input type="text" id="phone_number" name="phone_number"
                                value="{{ old('phone_number', $user->phone_number) }}">
                        </div>

                        <!-- Profile Picture -->
                        <div class="profile-edit-field">
                            <label for="profile_picture">
                                <i class="fas fa-image"></i> Profile Picture
                            </label>
                            <input type="file" id="profile_picture" name="profile_picture" class="form-control"
                                accept="image/*" onchange="previewImage(event)">

                            @if ($user->profile && $user->profile->profile_picture)
                                <div>
                                    <img id="profile_picture_preview"
                                        src="{{ Storage::url($user->profile->profile_picture) }}" alt="Profile Picture"
                                        width="100">
                                </div>
                            @else
                                <div id="profile_picture_preview"></div>
                            @endif
                        </div>

                        <!-- Address Dropdown and Button next to each other -->
                        <div class="select-address-container">
                            <div class="profile-edit-field">
                                <label for="address_id">
                                    <i class="fas fa-home"></i> Select Address
                                </label>
                                <select name="address_id" id="address_id" class="form-control">
                                    <option value="" disabled selected>Select Address</option>
                                    @foreach ($addresses as $address)
                                        <option value="{{ $address->id }}"
                                            {{ old('address_id', $user->profile->address_id) == $address->id ? 'selected' : '' }}>
                                            {{ $address->address_line1 }}, {{ $address->city }}, {{ $address->country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="profile-edit-actions">
                                <a href="{{ route('profile.address.add') }}" class="profile-edit-btn secondary">Add New
                                    Address</a>
                            </div>
                        </div>

                        <!-- Gender Field -->
                        <div class="profile-edit-field">
                            <label for="gender">
                                <i class="fas fa-venus-mars"></i> Gender
                            </label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="" disabled selected>Choose Your Gender!</option>
                                <option value="male"
                                    {{ old('gender', optional($user->profile)->gender) == 'male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="female"
                                    {{ old('gender', optional($user->profile)->gender) == 'female' ? 'selected' : '' }}>
                                    Female</option>
                                <option value="other"
                                    {{ old('gender', optional($user->profile)->gender) == 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                        </div>

                        <!-- Date of Birth Field -->
                        <div class="profile-edit-field">
                            <label for="date_of_birth">
                                <i class="fas fa-birthday-cake"></i> Date of Birth
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth', optional($user->profile)->date_of_birth) }}">
                        </div>

                        <!-- Password Field (optional) -->
                        <div class="profile-edit-field">
                            <label for="password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" id="password" name="password">
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="profile-edit-field">
                            <label for="password_confirmation">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="profile-edit-actions">
                        <button type="submit" class="profile-edit-btn primary">Save Changes</button>
                        <a href="{{ url('/dashboard') }}" class="profile-edit-btn secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
