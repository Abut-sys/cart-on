@extends('layouts.index')

@section('title', 'Manage Addresses')

@section('content')
    <div class="profile-edit-card-container">
        <div class="profile-edit-card">
            <div class="profile-edit-header">
                <h3>Manage Your Addresses</h3>
                <p>Add, edit, or delete your addresses here</p>
            </div>
            <div class="profile-edit-body">
                
                <!-- Add Address Form -->
                <form action="{{ route('profile.address.add') }}" method="POST">
                    @csrf

                    <div class="profile-edit-field">
                        <label for="address_line1">
                            <i class="fas fa-home"></i> Address Line 1
                        </label>
                        <input type="text" id="address_line1" name="address_line1" value="{{ old('address_line1') }}"
                            required>
                    </div>

                    <div class="profile-edit-field">
                        <label for="address_line2">
                            <i class="fa-solid fa-house-crack"></i> More Detailed Address (Optional)
                        </label>
                        <input type="text" id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
                    </div>

                    <div class="profile-edit-field">
                        <label for="city">
                            <i class="fas fa-city"></i> City
                        </label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" required>
                    </div>

                    <div class="profile-edit-field">
                        <label for="state">
                            <i class="fas fa-location-arrow"></i> State (Optional)
                        </label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}">
                    </div>

                    <div class="profile-edit-field">
                        <label for="postal_code">
                            <i class="fa-brands fa-usps"></i> Postal Code
                        </label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                    </div>

                    <div class="profile-edit-field">
                        <label for="country">
                            <i class="fas fa-globe"></i> Country
                        </label>
                        <input type="text" id="country" name="country" value="{{ old('country') }}" required>
                    </div>

                    <div class="profile-edit-actions mt-2">
                        <button type="submit" class="profile-edit-btn primary">Add Address</button>
                    </div>
                </form>

                @if ($addresses->count())
                    <div class="addresses-list">
                        @foreach ($addresses as $address)
                            <div class="address-item">
                                <p><strong>Address Line 1:</strong> {{ $address->address_line1 }}</p>
                                <p><strong>More Detailed Address:</strong> {{ $address->address_line2 }}</p>
                                <p><strong>City:</strong> {{ $address->city }}</p>
                                <p><strong>State:</strong> {{ $address->state }}</p>
                                <p><strong>Postal Code:</strong> {{ $address->postal_code }}</p>
                                <p><strong>Country:</strong> {{ $address->country }}</p>

                                <form action="{{ route('profile.address.delete', $address->id) }}" method="POST"
                                    class="delete-address-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-address-btn">Delete</button>
                                </form>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                @else
                    <p>You don't have any saved addresses yet.</p>
                @endif

                <!-- Back to Edit Profile Button -->
                <div class="profile-edit-actions">
                    <a href="{{ route('profile.edit') }}" class="profile-edit-btn secondary">Back to Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
@endsection
