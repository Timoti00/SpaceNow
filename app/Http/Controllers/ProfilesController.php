<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfilesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = User::with('profiles')->findOrFail($user->id);

        // Jika tidak ada relasi profile, buat objek kosong agar tidak error di blade
        if (!$profile->profiles) {
            $profile->profiles = (object)[
                'full_name' => null,
                'date_of_birth' => null,
                'gender' => null,
                'phone_number' => null,
                'employee_id' => null,
                'position' => null,
                'degree' => null,
                'faculty' => null,
                'study_program' => null,
                'nrp' => null,
                'batch_year' => null,
            ];
        }

        return view('users.profiles', compact('profile', 'user'));
    }
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $profile = User::with('profiles')->findOrFail($id);

        // Validasi umum
        $data = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // Tambahan per role
        if ($user->role == 'dosen') {
            $data += $request->validate([
                'employee_id' => 'nullable|string|max:50',
                'position' => 'nullable|string|max:100',
                'degree' => 'nullable|string|max:50',
                'faculty' => 'nullable|string|max:100',
                'study_program' => 'nullable|string|max:100',
            ]);
        } elseif ($user->role == 'user') {
            $data += $request->validate([
                'nrp' => 'nullable|string|max:50',
                'faculty' => 'nullable|string|max:100',
                'study_program' => 'nullable|string|max:100',
                'batch_year' => 'nullable|integer',
            ]);
        }

        $profile->profiles->updateOrCreate(['user_id' => $profile->id], $data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }




}