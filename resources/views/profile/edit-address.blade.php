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
                            <input type="text" id="address_line1" name="address_line1" value="{{ old('address_line1') }}"
                                required>
                            <ul id="autocomplete-results" class="autocomplete-results"></ul>
                        </div>

                        <div class="profile-edit-field">
                            <label for="address_line2">
                                <i class="fa-solid fa-house-crack"></i> More Detailed Address (Optional)
                            </label>
                            <input type="text" id="address_line2" name="address_line2"
                                value="{{ old('address_line2') }}">
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
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                required>
                        </div>

                        <div class="profile-edit-field">
                            <label for="country">
                                <i class="fas fa-globe"></i> Country
                            </label>
                            <input type="text" id="country" name="country" value="{{ old('country') }}" required>
                        </div>

                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                        <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id') }}">

                        <div class="profile-edit-actions mt-2">
                            <button type="submit" class="profile-edit-btn primary">Add Address</button>
                        </div>
                    </div>
                </form>

                @if ($addresses->count())
                    <div class="addresses-list">
                        @foreach ($addresses as $address)
                            <div class="address-item pors">
                                <div class="pors-body">
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

    <script>
        const input = document.getElementById('address_line1');
        const resultBox = document.getElementById('autocomplete-results');

        resultBox.addEventListener('mousedown', function(e) {
            e.preventDefault();
        });

        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        const fetchAutocomplete = debounce(function() {
            const query = input.value;
            if (query.length < 3) {
                resultBox.innerHTML = '';
                return;
            }

            fetch(`/autocomplete/address?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    resultBox.innerHTML = '';
                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = item.display_name;
                        li.classList.add('autocomplete-item');

                        li.addEventListener('click', () => {
                            input.value = item.display_name;

                            document.getElementById('city').value =
                                item.address.city || item.address.town || item.address
                                .village || '';

                            document.getElementById('state').value =
                                item.address.state || item.address.region || item.address
                                .county || '';

                            document.getElementById('country').value =
                                item.address.country || '';

                            document.getElementById('postal_code').value =
                                item.address.postcode || '';

                            document.getElementById('latitude').value = item.lat || '';
                            document.getElementById('longitude').value = item.lon || '';

                            document.getElementById('city_id').value = '';

                            resultBox.innerHTML = '';
                        });

                        resultBox.appendChild(li);
                    });
                })
                .catch(err => {
                    console.error('Autocomplete error:', err);
                    resultBox.innerHTML = '';
                });
        }, 200);

        input.addEventListener('input', fetchAutocomplete);
    </script>
@endsection
