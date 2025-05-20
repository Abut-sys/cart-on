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
                        <input type="text" id="address_line1" name="address_line1"
                               value="{{ old('address_line1') }}" required>
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
                        <input type="text" id="postal_code" name="postal_code"
                               value="{{ old('postal_code') }}" required>
                    </div>

                    <div class="profile-edit-field">
                        <label for="country">
                            <i class="fas fa-globe"></i> Country
                        </label>
                        <input type="text" id="country" name="country"
                               value="{{ old('country') }}" required>
                    </div>

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

                                <form action="{{ route('profile.address.delete', $address->id) }}"
                                      method="POST" class="delete-address-form">
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

<style>
    /* Profile Card Styling */
    .profile-edit-card-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
    }

    .profile-edit-card {
        width: 100%;
        max-width: 800px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .profile-edit-card:hover {
        transform: translateY(-4px);
    }

    .profile-edit-header h3 {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
    }

    .profile-edit-header h3::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: #4a90e2;
        margin: 0.5rem auto 0;
        border-radius: 2px;
    }

    .profile-edit-field {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .profile-edit-field label {
        display: flex;
        align-items: center;
        font-size: 14px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }

    .profile-edit-field label i {
        margin-right: 0.75rem;
        color: #718096;
        width: 20px;
        text-align: center;
    }

    .profile-edit-field input {
        width: 100%;
        padding: 0.875rem 1.25rem;
        font-size: 14px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .profile-edit-field input:focus {
        border-color: #4a90e2;
        background: #ffffff;
        box-shadow: 0 3px 6px rgba(74, 144, 226, 0.1);
    }

    .autocomplete-results {
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        z-index: 100;
        margin-top: 0.25rem;
    }

    .autocomplete-item {
        padding: 0.75rem 1.25rem;
        font-size: 14px;
        color: #4a5568;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .autocomplete-item:hover {
        background: #f7fafc;
        transform: translateX(4px);
    }

    .addresses-list {
        margin-top: 2.5rem;
        border-top: 2px solid #edf2f7;
        padding-top: 2rem;
    }

    .address-item {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .address-item:hover {
        transform: translateY(-2px);
        border-color: #c3dafe;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
    }

    .address-item p {
        margin: 0.4rem 0;
        color: #4a5568;
        font-size: 14px;
    }

    .address-item p strong {
        color: #2d3748;
        font-weight: 600;
        min-width: 140px;
        display: inline-block;
    }

    .delete-address-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #fee2e2;
        color: #dc2626;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .delete-address-btn:hover {
        background: #fecaca;
        color: #b91c1c;
        transform: scale(1.05);
    }

    .profile-edit-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .profile-edit-btn {
        padding: 0.875rem 1.75rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .profile-edit-btn.primary {
        background: #4a90e2;
        color: white;
        border: none;
    }

    .profile-edit-btn.primary:hover {
        background: #357abd;
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
    }

    .profile-edit-btn.cancel {
        background: #edf2f7;
        color: #4a5568;
        text-decoration: none;
    }

    .profile-edit-btn.cancel:hover {
        background: #e2e8f0;
        color: #2d3748;
    }

    .no-addresses {
        text-align: center;
        padding: 2rem;
        color: #a0aec0;
        font-style: italic;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        margin: 2rem 0;
    }

    @media (max-width: 768px) {
        .profile-edit-card {
            padding: 1.5rem;
        }

        .address-item p strong {
            display: block;
            margin-bottom: 0.25rem;
        }
    }
</style>

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
                        input.value = item.address.road || item.display_name;
                        document.getElementById('city').value = item.address.city ||
                            item.address.town || item.address.village || '';
                        document.getElementById('state').value = item.address.state ||
                            item.address.region || item.address.county || '';
                        document.getElementById('country').value = item.address.country || '';
                        document.getElementById('postal_code').value = item.address.postcode || '';
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
