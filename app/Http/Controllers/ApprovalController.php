<?php

namespace App\Http\Controllers;

use App\Models\RoomBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $bookings = RoomBookings::with('user', 'files')
            ->where('status', 'pending') // hanya status pending
            ->orderByRaw("CASE 
                            WHEN status = 'pending' THEN 0 
                            WHEN status = 'approved' THEN 1 
                            WHEN status = 'rejected' THEN 2 
                            ELSE 3 
                        END")
            ->orderBy('created_at', 'asc')
            ->latest('id') // fallback kalau created_at sama
            ->get();

        return view('book_room.approval', compact('bookings', 'user'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $booking = RoomBookings::findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking already processed.');
        }

        if ($request->action === 'approve') {
            $booking->status = 'approved';
            $booking->approved_by = auth()->id();
            $booking->approval_time = now();
            $booking->save();

            // Otomatis tolak semua booking lain yang bentrok waktunya
            RoomBookings::where('id', '!=', $booking->id)
                ->where('room_id', $booking->room_id)
                ->where('status', 'pending')
                ->where(function ($query) use ($booking) {
                    $query->whereBetween('start_time', [$booking->start_time, $booking->end_time])
                        ->orWhereBetween('end_time', [$booking->start_time, $booking->end_time])
                        ->orWhere(function ($query) use ($booking) {
                            $query->where('start_time', '<', $booking->start_time)
                                    ->where('end_time', '>', $booking->end_time);
                        });
                })
                ->update([
                    'status' => 'rejected',
                    'approved_by' => auth()->id(),
                    'approval_time' => now(),
                ]);

        } elseif ($request->action === 'reject') {
            $booking->status = 'rejected';
            $booking->approved_by = auth()->id();
            $booking->approval_time = now();
            $booking->save();
        } elseif ($request->action === 'cancel') {
            $booking->status = 'cancelled';
            $booking->save();
        }

        return back()->with('success', 'Booking has been ' . $request->action . 'd.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}