<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Models\Address;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                // Ensure profile exists
                Auth::user()->profile()->firstOrCreate([
                    'user_id' => Auth::id()
                ]);
            }
            return $next($request);
        });
    }

    // Show edit profile form
    public function edit()
    {
        $user = Auth::user()->load(['profile.addresses']);
        $profile = $user->profile;
        $addresses = $profile->addresses;

        if ($user->hasRole('admin')) {
            return view('profile.edit_admin', compact('user', 'profile', 'addresses'));
        }

        return view('profile.edit', compact('user', 'profile', 'addresses'));
    }

    // profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:15',
            'password' => 'nullable|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'address_id' => 'nullable|exists:addresses,id',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('phone_number')) {
            $user->phone_number = $request->phone_number;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $user->save();

        $profile = $user->profile;

        if ($request->hasFile('profile_picture')) {
            if ($profile->profile_picture) {
                Storage::delete('public/profile_pictures/' . $profile->profile_picture);
            }
            $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
            $profile->profile_picture = basename($profilePicture);
        }

        $profile->gender = $request->input('gender', $profile->gender);
        $profile->date_of_birth = $request->input('date_of_birth', $profile->date_of_birth);

        if ($request->filled('address_id')) {
            $profile->address_id = $request->input('address_id');
        }

        $profile->save();

        return redirect()->route('profile.edit')->with('msg', 'Profile updated successfully!');
    }

    // Show address edit form
    public function editAddress()
    {
        $user = Auth::user()->load(['profile.addresses']);

        if ($user->hasRole('admin')) {
            abort(403, 'Admin cannot edit addresses.');
        }

        $addresses = $user->profile->addresses;
        return view('profile.edit-address', compact('user', 'addresses'));
    }

    // Add new address
    public function addAddress(Request $request)
    {
        $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'city_id' => 'nullable|string',
        ]);

        $user = Auth::user()->load('profile');
        $profile = $user->profile;

        $addressString = implode(', ', [
            $request->address_line1,
            $request->city,
            $request->state,
            $request->postal_code,
            $request->country
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        if (!$latitude || !$longitude) {
            [$latitude, $longitude] = $this->getCoordinatesFromAddress($addressString);
        }

        $newAddress = $profile->addresses()->create([
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'city_id' => $request->city_id ?: null,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        if ($profile->addresses()->count() === 1) {
            $profile->update(['address_id' => $newAddress->id]);
        }

        return redirect()->route('profile.address.edit')->with('msg', 'Address added successfully!');
    }

    // Get coordinates from address
    private function getCoordinatesFromAddress($address)
    {
        $response = Http::withHeaders([
            'User-Agent' => config('app.name')
        ])->get('https://nominatim.openstreetmap.org/search', [
            'format' => 'json',
            'q' => $address,
        ]);

        $data = $response->json();

        return !empty($data)
            ? [$data[0]['lat'], $data[0]['lon']]
            : [null, null];
    }

    // Address autocomplete
    public function autocompleteAddress(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $response = Http::withHeaders([
            'User-Agent' => config('app.name')
        ])->get('https://nominatim.openstreetmap.org/search', [
            'street' => $query,
            'format' => 'json',
            'addressdetails' => 1,
            'limit' => 5,
        ]);

        return response()->json($response->json());
    }

    // Delete address
    public function deleteAddress($id)
    {
        $address = Address::findOrFail($id);

        if ($address->profile_id != Auth::user()->profile->id) {
            abort(403, 'Unauthorized action.');
        }

        $address->delete();

        return redirect()->route('profile.address.edit')->with('msg', 'Address deleted successfully!');
    }
}
