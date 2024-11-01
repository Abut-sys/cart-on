<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CostumersController extends Controller
{
    public function index()
    {
        $users = User::all();
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
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('images', 'public')
            : null;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
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
