<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CostumersController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nilai dari query string
        $search = $request->input('search');
        $role = $request->input('role');
        $sortId = $request->input('sort_id');
        $sortName = $request->input('sort_name');

        // Query dasar
        $query = User::query();

        // Filter pencarian berdasarkan nama atau email
        if ($search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        }

        // Filter berdasarkan role
        if ($role && $role != 'all') {
            $query->where('role', $role);
        }

        // Filter pengurutan berdasarkan ID
        if ($sortId) {
            $query->orderBy('id', $sortId);
        }

        // Filter pengurutan berdasarkan nama
        if ($sortName) {
            $query->orderBy('name', $sortName);
        }

        // Ambil hasil dengan pagination
        $users = $query->paginate(10);

        // Kirim data ke view
        return view('costumers.index', compact('users'));
    }

    public function create()
    {
        return view('costumers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20|unique:users',
            'role' => 'required|string|in:user,admin',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => Carbon::now(),
            'image_url' => $imagePath,
        ]);

        return redirect()->route('costumers.index')->with('success', 'User added successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->image_url) {
            Storage::disk('public')->delete($user->image_url);
        }
        $user->delete();

        return redirect()->route('costumers.index')->with('success', 'User deleted successfully.');
    }
}
