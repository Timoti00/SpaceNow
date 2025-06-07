<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Profiles;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
     // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/'); // atau ke /dashboard atau /index
        }

        return back()->withErrors([
            'email' => 'Wrong Credentials',
        ]);
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register'); // Pastikan file blade-nya ada di resources/views/auth/register.blade.php
    }

    public function register(Request $request)
    {
        // Validasi umum
        $request->validate([
            'role' => ['required', Rule::in(['dosen', 'user'])],
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Validasi khusus role dosen
        if ($request->role === 'dosen') {
            $request->validate([
                'employee_id' => 'nullable|integer',
                'degree' => 'nullable|string|max:255',
                'faculty' => 'nullable|string|max:255',
                'study_program' => 'nullable|string|max:255',
            ]);
        }

        // Validasi khusus role user (mahasiswa)
        if ($request->role === 'user') {
            $request->validate([
                'nrp' => 'nullable|integer',
                'faculty' => 'nullable|string|max:255',
                'study_program' => 'nullable|string|max:255',
                'batch_year' => 'nullable|string|max:10',
            ]);
        }

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // Buat profil user
        $profileData = [
            'user_id' => $user->id,
            'full_name' => $request->name,
            // Anda bisa tambah input tambahan seperti date_of_birth, gender, phone_number jika ada di form
        ];

        if ($request->role === 'dosen') {
            $profileData['employee_id'] = $request->employee_id;
            $profileData['degree'] = $request->degree;
            $profileData['faculty'] = $request->faculty;
            $profileData['study_program'] = $request->study_program;
        } elseif ($request->role === 'user') {
            $profileData['nrp'] = $request->nrp;
            $profileData['faculty'] = $request->faculty;
            $profileData['study_program'] = $request->study_program;
            $profileData['batch_year'] = $request->batch_year;
        }

        Profiles::create($profileData);

        // Redirect atau login otomatis jika ingin
        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

}