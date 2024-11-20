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
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="profile-edit-fields">
                    <div class="profile-edit-field">
                        <label for="name">
                            <i class="fas fa-user"></i> Name
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="profile-edit-field">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="profile-edit-field">
                        <label for="phone_number">
                            <i class="fas fa-phone"></i> Phone
                        </label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                    </div>
                    <div class="profile-edit-field">
                        <label for="password">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input type="password" id="password" name="password">
                    </div>
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
