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

        $bookings = RoomBookings::with('user','files')
            ->where('user_id', $user->id)
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 0 
                WHEN status = 'approved' THEN 1 
                WHEN status = 'rejected' THEN 2 
                ELSE 3 
            END")
            ->orderBy('created_at', 'asc')
            ->get();
     

        return view('book_room.history', compact('bookings','user'));
    }

}