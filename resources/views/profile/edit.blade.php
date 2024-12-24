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
                <ul class="profile-edit-tabs">
                    <li id="tab-1" class="active" onclick="showTab(1)">Personal Info</li>
                    <li id="tab-2" onclick="showTab(2)">Account Info</li>
                    <li id="tab-3" onclick="showTab(3)">Address Info</li>
                </ul>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div id="tab-content-1" class="tab-content active">
                        <div class="profile-edit-field horizontal">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="phone_number">Phone</label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($user->profile)->date_of_birth) }}">
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender">
                                <option value="" disabled selected>Select Gender</option>
                                <option value="male" {{ old('gender', optional($user->profile)->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', optional($user->profile)->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', optional($user->profile)->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div id="tab-content-2" class="tab-content">
                        <div class="profile-edit-field horizontal">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password">
                        </div>

                        <div class="profile-edit-field horizontal">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div id="tab-content-3" class="tab-content">
                        <div class="profile-edit-field horizontal">
                            <label for="address_id">Address</label>
                            <select name="address_id" id="address_id">
                                <option value="" disabled selected>Select Address</option>
                                @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}" {{ old('address_id', optional($user->profile)->address_id) == $address->id ? 'selected' : '' }}>{{ $address->address_line1 }}, {{ $address->city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="profile-edit-actions mt-2">
                            <a href="{{ route('profile.address.add') }}" class="profile-edit-btn secondary">Add New Address</a>
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
        function showTab(tabIndex) {
            const tabs = document.querySelectorAll('.profile-edit-tabs li');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach((tab, index) => {
                tab.classList.toggle('active', index + 1 === tabIndex);
            });

            contents.forEach((content, index) => {
                content.classList.toggle('active', index + 1 === tabIndex);
            });
        }
    </script>
@endsection
