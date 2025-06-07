<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index()
    {
        $user = auth()->user();
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users', 'user'));
    }

    public function create()
    {
        $user = auth()->user();
        return view('users.form', compact('user'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,user', // Tambahkan validasi untuk role
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $users = User::findOrFail($id);
        return view('users.form', compact('users', 'user'));
    }




    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user', // Tambahkan validasi untuk role
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}