<?php

namespace App\Http\Controllers;

use App\Models\Floor;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFloorRequest;
use App\Http\Requests\UpdateFloorRequest;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $data = Floor::all();
        return view('floor.index', compact(['user', 'data']));
    }

    // Untuk create
    public function create()
    {
        $user = auth()->user();
        return view('floor.form', compact('user'));
    }

    // Untuk edit
    public function edit($id)
    {
        $user = auth()->user();
        $floor = Floor::findOrFail($id);
        return view('floor.form', compact(['user', 'floor']));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Floor::create([
            'name' => $request->name,
        ]);

        return redirect()->route('floor.index')->with('success', 'Floor created successfully.');
    }


    public function update(Request $request, Floor $floor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $floor->update([
            'name' => $request->name,
        ]);

        return redirect()->route('floor.index')->with('success', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor)
    {
        $floor->delete();
        return redirect()->route('floor.index')->with('success', 'Floor deleted successfully.');
    }
}