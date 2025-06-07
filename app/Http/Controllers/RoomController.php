<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;

class RoomController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $floors = Floor::all();
        $rooms = Room::all();

        return view('room.index', compact('floors', 'rooms', 'user'));
    }
// Tampilkan halaman peta lantai dengan semua room
    public function showFloorMap($floorId)
    {
        $user = auth()->user();
        $floor = Floor::findOrFail($floorId);
        $rooms = Room::where('floor_id', $floorId)->get();

        return view('room.map', compact('floor', 'rooms', 'user'));
    }

    // Update posisi room via AJAX
    public function updatePosition(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:rooms,id',
            'position_x' => 'required|integer',
            'position_y' => 'required|integer',
        ]);

        $room = Room::find($request->id);
        $room->position_x = $request->position_x;
        $room->position_y = $request->position_y;
        $room->save();

        return response()->json(['message' => 'Position updated']);
    }
    public function show($id)
    {
        $room = Room::findOrFail($id);

        return response()->json($room);
    }


    // Simpan room baru dari form
    public function store(Request $request)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'room_code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'position_x' => 'nullable|integer',
            'position_y' => 'nullable|integer',
            'width' => 'nullable|integer',
            'height' => 'nullable|integer',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        Room::create([
            'floor_id' => $request->floor_id,
            'room_code' => $request->room_code,
            'name' => $request->name,
            'position_x' => $request->position_x ?? 0,
            'position_y' => $request->position_y ?? 0,
            'width' => $request->width ?? 50,
            'height' => $request->height ?? 50,
            'color' => $request->color ?? '#007bff',
            'description' => $request->description,
        ]);

        return redirect()->route('floor.map', $request->floor_id)->with('success', 'Room added');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'room_code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'position_x' => 'nullable|integer',
            'position_y' => 'nullable|integer',
            'width' => 'nullable|integer',
            'height' => 'nullable|integer',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $room = Room::findOrFail($id);

        $room->update([
            'floor_id' => $request->floor_id,
            'room_code' => $request->room_code,
            'name' => $request->name,
            'position_x' => $request->position_x ?? 0,
            'position_y' => $request->position_y ?? 0,
            'width' => $request->width ?? 50,
            'height' => $request->height ?? 50,
            'color' => $request->color ?? '#007bff',
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Room updated',
            'redirect' => route('floor.map', $request->floor_id),
        ]);
    }


    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json(['success' => true]);
    }

}