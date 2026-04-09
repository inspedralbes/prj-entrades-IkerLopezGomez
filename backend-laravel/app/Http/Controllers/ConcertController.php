<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\ConcertSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConcertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Concert::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titol' => 'required|string|max:255',
            'artista' => 'required|string|max:255',
            'descripcio' => 'nullable|string',
            'data' => 'required|date',
            'hora' => 'required',
            'preu' => 'required|numeric|min:0',
            'imatge_url' => 'nullable|string',
        ]);

        $concert = Concert::create($validated);
        
        $basePrice = floatval($validated['preu']);
        
        $zones = [
            'P1' => ['rows' => 1, 'seats' => 75, 'extra' => 20],
            'P2' => ['rows' => 1, 'seats' => 75, 'extra' => 10],
            'L' => ['rows' => 1, 'seats' => 10, 'extra' => 0],
            'R' => ['rows' => 1, 'seats' => 10, 'extra' => 0],
            'G' => ['rows' => 1, 'seats' => 20, 'extra' => 0],
        ];
        
        $seats = [];
        $now = Carbon::now();
        foreach ($zones as $zone => $config) {
            for ($s = 1; $s <= $config['seats']; $s++) {
                $seats[] = [
                    'concert_id' => $concert->id,
                    'row' => $zone,
                    'number' => $s,
                    'status' => 0,
                    'preu' => $basePrice + $config['extra'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        
        DB::table('concert_seats')->insert($seats);
        
        return response()->json($concert, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Concert::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $concert = Concert::findOrFail($id);
        
        $validated = $request->validate([
            'titol' => 'sometimes|string|max:255',
            'artista' => 'sometimes|string|max:255',
            'descripcio' => 'nullable|string',
            'data' => 'sometimes|date',
            'hora' => 'sometimes',
            'preu' => 'sometimes|numeric|min:0',
            'imatge_url' => 'nullable|string',
        ]);

        $concert->update($validated);
        return response()->json($concert);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $concert = Concert::findOrFail($id);
        $concert->delete();
        return response()->json(['message' => 'Concert eliminat']);
    }
}