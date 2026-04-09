<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;

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