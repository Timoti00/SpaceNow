<?php

namespace App\Http\Controllers;

use App\Models\RoomBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingHIstoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $bookings = RoomBookings::with('user', 'files')
            ->when($user->role === 'admin', function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    // Untuk semua user, hanya yang approved
                    $subQuery->where('status', 'approved');

                    // Tapi jika data milik admin sendiri, tampilkan semua status
                    $subQuery->orWhere('user_id', $user->id);
                });
            }, function ($query) use ($user) {
                // Jika bukan admin, hanya data milik sendiri
                $query->where('user_id', $user->id);
            })
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 0 
                WHEN status = 'approved' THEN 1 
                WHEN status = 'rejected' THEN 2 
                ELSE 3 
            END")
            ->orderBy('created_at', 'asc')
            ->get();

        return view('book_room.history', compact('bookings', 'user'));
    }
}