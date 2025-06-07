<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use App\Models\BookingFile;
use Illuminate\Support\Str;
use App\Models\RoomBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRoomBookingsRequest;
use App\Http\Requests\UpdateRoomBookingsRequest;

class RoomBookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::with('floor','bookings');
        $now = Carbon::now('Asia/Jakarta');
        $user = auth()->user();
        $date = $request->input('date', $now->format('Y-m-d'));
        $startTime = $request->input('start_time', $now->format('H:i'));
        $endTime = $request->input('end_time', $now->copy()->addHour()->format('H:i'));
        $floors = Floor::all();


        $startDateTime = $date . ' ' . $startTime . ':00';
        $endDateTime = $date . ' ' . $endTime . ':00';



        $query->whereDoesntHave('bookings', function ($q) use ($startDateTime, $endDateTime) {
            $q->where('status', 'approved')
            ->where(function ($q2) use ($startDateTime, $endDateTime) {
                $q2->whereBetween('start_time', [$startDateTime, $endDateTime])
                    ->orWhereBetween('end_time', [$startDateTime, $endDateTime])
                    ->orWhere(function ($q3) use ($startDateTime, $endDateTime) {
                        $q3->where('start_time', '<=', $startDateTime)
                            ->where('end_time', '>=', $endDateTime);
                    });
            });
        });

        
        $rooms = $query->get();


            // Semua rooms untuk map
        $allRooms = Room::with('bookings')->get();

        // Loop untuk menandai room booked atau tidak
        foreach ($allRooms as $room) {
            $isBooked = $room->bookings->contains(function ($booking) use ($startDateTime, $endDateTime) {
                return $booking->status == 'approved' &&
                    (
                        ($booking->start_time >= $startDateTime && $booking->start_time <= $endDateTime) ||
                        ($booking->end_time >= $startDateTime && $booking->end_time <= $endDateTime) ||
                        ($booking->start_time <= $startDateTime && $booking->end_time >= $endDateTime)
                    );
            });

            $room->color = $isBooked ? '#6c757d' : $room->color; // abu-abu kalau booked, biru kalau tidak
        }

        return view('book_room.index', compact('rooms', 'user','floors','allRooms'));
    }
    
    public function pollingIndex(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $date = $request->input('date', $now->format('Y-m-d'));
        $startTime = $request->input('start_time', $now->format('H:i'));
        $endTime = $request->input('end_time', $now->copy()->addHour()->format('H:i'));

        $startDateTime = $date . ' ' . $startTime . ':00';
        $endDateTime = $date . ' ' . $endTime . ':00';

        $rooms = Room::with('floor')->whereDoesntHave('bookings', function ($q) use ($startDateTime, $endDateTime) {
            $q->where('status', 'approved')
            ->where(function ($q2) use ($startDateTime, $endDateTime) {
                $q2->whereBetween('start_time', [$startDateTime, $endDateTime])
                    ->orWhereBetween('end_time', [$startDateTime, $endDateTime])
                    ->orWhere(function ($q3) use ($startDateTime, $endDateTime) {
                        $q3->where('start_time', '<=', $startDateTime)
                            ->where('end_time', '>=', $endDateTime);
                    });
            });
        })->get();

        $allRooms = Room::with('bookings')->get();

        foreach ($allRooms as $room) {
            $isBooked = $room->bookings->contains(function ($booking) use ($startDateTime, $endDateTime) {
                return $booking->status == 'approved' &&
                    (
                        ($booking->start_time >= $startDateTime && $booking->start_time <= $endDateTime) ||
                        ($booking->end_time >= $startDateTime && $booking->end_time <= $endDateTime) ||
                        ($booking->start_time <= $startDateTime && $booking->end_time >= $endDateTime)
                    );
            });

            $room->color = $isBooked ? '#6c757d' : '#007bff';
        }

        return response()->json([
            'rooms' => $rooms,
            'allRooms' => $allRooms
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        // Ambil parameter dari URL
        $room_id = $request->query('room_id');
        $date = $request->query('date');
        $start_time = $request->query('start_time');
        $end_time = $request->query('end_time');
        $room = Room::findOrFail($room_id);
        // Kirim data ke view
        return view('book_room.form', compact('room', 'room_id', 'date', 'start_time', 'end_time', 'user'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'required|string|max:1000',
            'document' => 'required|mimes:pdf|max:2048', // maksimal 2MB
        ]);

        // Gabungkan tanggal booking dengan jam mulai dan jam selesai
        $startDateTime = Carbon::parse($validated['booking_date'] . ' ' . $validated['start_time']);
        $endDateTime = Carbon::parse($validated['booking_date'] . ' ' . $validated['end_time']);

        // Cek konflik booking
        $conflict = RoomBookings::where('room_id', $validated['room_id'])
            ->where('start_time', '<', $endDateTime)
            ->where('end_time', '>', $startDateTime)
            ->where('status', 'approved') // hanya yang sudah disetujui
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'Room is already booked at the selected time.'])->withInput();
        }
        $path = $request->file('document')->store('bookfiles', 'public');


        // Simpan booking
        RoomBookings::create([
            'user_id' => Auth::id(),
            'room_id' => $validated['room_id'],
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'purpose' => $validated['purpose'],
            'status' => 'pending', // default
            'booking_code' => strtoupper(Str::random(10)),
        ]);
        BookingFile::create([
            'booking_id' => RoomBookings::latest()->first()->id,
            'file_path' => $path,
        ]);

        return redirect()->route('booking.index')->with('success', 'Room booked successfully. Waiting for approval.');
    }


    /**
     * Display the specified resource.
     */
    public function show(RoomBookings $roomBookings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $booking = RoomBookings::findOrFail($id);
        $rooms = Room::all();
        return view('book_room.form', compact('booking', 'rooms', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomBookingsRequest $request, RoomBookings $roomBookings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomBookings $roomBookings)
    {
        //
    }
}