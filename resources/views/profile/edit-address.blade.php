@extends('layouts.index')

@section('title', 'Manage Addresses')

@section('content')
    <div class="profile-edit-card-container">
        <div class="profile-edit-card">
            <div class="profile-edit-header">
                <h3>Manage Your Addresses</h3>
            </div>
            <div class="profile-edit-body">

                <!-- Add Address Form -->
                <form action="{{ route('profile.address.add') }}" method="POST">
                    @csrf
                    <div class="address-form-container">
                        <div class="profile-edit-field">
                            <label for="address_line1">
                                <i class="fas fa-home"></i> Address Line 1
                            </label>
                            <input type="text" id="address_line1" name="address_line1" value="{{ old('address_line1') }}" required>
                        </div>

                        <div class="profile-edit-field">
                            <label for="address_line2">
                                <i class="fa-solid fa-house-crack"></i> More Detailed Address (Optional)
                            </label>
                            <input type="text" id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
                        </div>

                        <div class="profile-edit-field">
                            <label for="state">
                                <i class="fas fa-location-arrow"></i> State
                            </label>
                            <input type="text" id="state" name="state" value="{{ old('state') }}">
                        </div>

                        <div class="profile-edit-field">
                            <label for="city">
                                <i class="fas fa-city"></i> City
                            </label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" required>
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
                    </div>
                </form>

                @if ($addresses->count())
                    <div class="addresses-list">
                        @foreach ($addresses as $address)
                            <div class="address-item card">
                                <div class="card-body">
                                    <p><strong>Address Line 1:</strong> {{ $address->address_line1 }}</p>
                                    <p><strong>More Detailed Address:</strong> {{ $address->address_line2 }}</p>
                                    <p><strong>State:</strong> {{ $address->state }}</p>
                                    <p><strong>City:</strong> {{ $address->city }}</p>
                                    <p><strong>Postal Code:</strong> {{ $address->postal_code }}</p>
                                    <p><strong>Country:</strong> {{ $address->country }}</p>

                                    <form action="{{ route('profile.address.delete', $address->id) }}" method="POST"
                                        class="delete-address-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-address-btn">Delete</button>
                                    </form>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                @else
                    <p class="no-addresses">You don't have any saved addresses yet.</p>
                @endif

                <!-- Back to Edit Profile Button -->
                <div class="profile-edit-actions">
                    <a href="{{ route('profile.edit') }}" class="profile-edit-btn cancel">Back to Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    /* Profile Card Styling */
    .profile-edit-card-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .profile-edit-card {
        width: 100%;
        max-width: 800px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 30px;
    }

    .profile-edit-header h3 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-edit-body {
        margin-top: 20px;
    }

    .profile-edit-field {
        margin-bottom: 15px;
    }

    .profile-edit-field label {
        font-size: 14px;
        font-weight: 600;
        color: #444;
        display: flex;
        align-items: center;
    }

    .profile-edit-field input {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-top: 5px;
    }

    .profile-edit-actions {
        display: flex;
        justify-content: right;
    }

    .address-item.card {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    .delete-address-btn {
        background-color: #e74c3c;
        color: #fff;
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .delete-address-btn:hover {
        background-color: #c0392b;
    }

    .no-addresses {
        text-align: center;
        font-size: 16px;
        color: #888;
    }
</style>
