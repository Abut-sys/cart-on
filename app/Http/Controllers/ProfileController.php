<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Models\Address;

class ProfileController extends Controller
{
    // Tampilkan form edit profil
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;
        // Using `collect([])` to ensure $addresses is always a Collection
        $addresses = $profile ? $profile->addresses : collect([]);

        if ($user->hasRole('admin')) {
            return view('profile.edit_admin', compact('user', 'profile', 'addresses'));
        }

        return view('profile.edit', compact('user', 'profile', 'addresses'));
    }

    // Proses update profil
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
            'address_id' => 'nullable|exists:addresses,id', // Menambahkan validasi untuk address_id
        ]);

        // Update data pengguna (user)
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('phone_number')) {
            $user->phone_number = $request->phone_number;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update profil atau buat profil baru jika belum ada
        $profile = $user->profile ?: new Profile();
        $profile->user_id = $user->id;

        if ($request->hasFile('profile_picture')) {
            if ($user->profile && $user->profile->profile_picture) {
                Storage::delete('public/profile_pictures/' . $user->profile->profile_picture);
            }
            $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile->profile_picture = basename($profilePicture); // Menyimpan nama file
        }

        // Update gender dan date_of_birth
        $profile->gender = $request->input('gender', $profile->gender);
        $profile->date_of_birth = $request->input('date_of_birth', $profile->date_of_birth);

        // Menambahkan alamat yang dipilih
        if ($request->filled('address_id')) {
            $profile->address_id = $request->input('address_id');
        }

        // Simpan profil
        $profile->save();

        return redirect()->route('profile.edit')->with('msg', 'Profile updated successfully!');
    }

    // Tampilkan halaman edit alamat
    public function editAddress()
    {
        $user = Auth::user();
        $profile = $user->profile;
        $addresses = $profile ? $profile->addresses : []; // Ambil alamat jika ada

        if ($user->hasRole('admin')) {
            abort(403, 'Admin cannot edit addresses.');
        }

        return view('profile.edit-address', compact('user', 'addresses'));
    }

    // Proses update alamat
    public function updateAddress(Request $request, $addressId)
    {
        $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $address = Address::findOrFail($addressId);
        $address->update($request->only(['address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country']));

        return redirect()->route('profile.address.edit')->with('msg', 'Address updated successfully!');
    }

    // Tambahkan alamat baru
    public function addAddress(Request $request)
    {
        $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $profile = $user->profile;

        // Membuat alamat baru dan menambahkannya ke profil
        $profile->addresses()->create($request->only(['address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country']));

        return redirect()->route('profile.address.edit')->with('msg', 'Address added successfully!');
    }

    // Hapus alamat
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
