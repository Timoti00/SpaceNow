<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use App\Models\RoomBooking;
use App\Models\RoomBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $floor = Floor::all()->count();
        $room =  Room::all()->count();

        if ($user->role == 'admin') {
            // Jika admin, ambil semua data peminjaman ruangan
            $roomBookings = RoomBookings::with('room')->orderBy('created_at', 'desc')->limit(10)->get();
        } else {
            // Jika bukan admin (misal role 'user' atau 'dosen'), ambil data peminjaman hanya milik user tersebut
            $roomBookings = RoomBookings::with('room')->where('user_id', $user->id)->orderBy('created_at', 'desc')->limit(10)->get();
        }

        // Total peminjaman (booking)
        if ($user->role == 'admin') {
            $totalBookings = RoomBooking::count();

            $activeBookings = RoomBooking::where('status', 'approved')->count();

            $upcomingBookings = RoomBooking::where('status', 'approved')
                ->where('start_time', '>', now())
                ->count();

            $rejectedBookings = RoomBooking::where('status', 'rejected')->count();

            $newRequests = RoomBooking::where('status', 'pending')->count();

            // Misal most booked room berdasarkan room_id, hitung dan ambil room_id paling banyak
            $mostBookedRoom = RoomBooking::select('room_id')
                ->groupBy('room_id')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(1)
                ->pluck('room_id')
                ->first();

        } else {
            // Untuk user biasa, batasi data hanya milik user itu saja
            $totalBookings = RoomBooking::where('user_id', $user->id)->count();

            $activeBookings = RoomBooking::where('user_id', $user->id)
                ->where('status', 'approved')->count();

            $upcomingBookings = RoomBooking::where('user_id', $user->id)
                ->where('status', 'approved')
                ->where('start_time', '>', now())
                ->count();

            $rejectedBookings = RoomBooking::where('user_id', $user->id)
                ->where('status', 'rejected')->count();

            $newRequests = RoomBooking::where('user_id', $user->id)
                ->where('status', 'pending')->count();

            $mostBookedRoom = RoomBooking::where('user_id', $user->id)
                ->select('room_id')
                ->groupBy('room_id')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(1)
                ->pluck('room_id')
                ->first();
        }

        // Average Booking Duration (misal dalam jam)
        $averageDuration = RoomBooking::selectRaw('AVG(TIMESTAMPDIFF(HOUR, start_time, end_time)) as avg_hours')
            ->when($user->role != 'admin', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->value('avg_hours');


        return view('index', compact('user', 'roomBookings','room', 'floor',            'totalBookings',
            'activeBookings',
            'upcomingBookings',
            'rejectedBookings',
            'newRequests',
            'mostBookedRoom',
            'averageDuration'));
    }

    public function earningsOverview(Request $request)
    {
        $interval = $request->input('interval', 'month');
        $now = Carbon::now();
        $user = auth()->user();

        $query = RoomBooking::where('status', 'approved');

        // Jika user bukan admin, batasi data hanya untuk user yang login
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        if ($interval === 'week') {
            $startDate = $now->copy()->subWeeks(11)->startOfWeek();
            $endDate = $now->copy()->endOfWeek();

            $data = $query->selectRaw("YEAR(approval_time) as year, WEEK(approval_time, 1) as week, COUNT(*) as total")
                ->whereBetween('approval_time', [$startDate, $endDate])
                ->groupBy('year', 'week')
                ->orderBy('year')
                ->orderBy('week')
                ->get();

            $labels = [];
            $values = [];

            for ($i = 0; $i < 12; $i++) {
                $date = $startDate->copy()->addWeeks($i);
                $year = $date->year;
                $week = $date->weekOfYear;
                $label = "Week {$week}, {$year}";
                $labels[] = $label;

                $found = $data->firstWhere(function($item) use ($year, $week) {
                    return $item->year == $year && $item->week == $week;
                });

                $values[] = $found ? $found->total : 0;
            }
        } else {
            $startDate = $now->copy()->subMonths(11)->startOfMonth();
            $endDate = $now->copy()->endOfMonth();

            $data = $query->selectRaw("YEAR(approval_time) as year, MONTH(approval_time) as month, COUNT(*) as total")
                ->whereBetween('approval_time', [$startDate, $endDate])
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            $labels = [];
            $values = [];

            for ($i = 0; $i < 12; $i++) {
                $date = $startDate->copy()->addMonths($i);
                $year = $date->year;
                $month = $date->month;
                $label = $date->format('M Y');
                $labels[] = $label;

                $found = $data->firstWhere(function($item) use ($year, $month) {
                    return $item->year == $year && $item->month == $month;
                });

                $values[] = $found ? $found->total : 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'interval' => $interval,
        ]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}