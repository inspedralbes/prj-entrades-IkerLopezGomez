<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Movie::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titol' => 'required|string|max:255',
            'descripcio' => 'nullable|string',
            'data' => 'required|date',
            'hora' => 'required',
            'preu' => 'required|numeric|min:0',
            'imatge_url' => 'nullable|string',
        ]);

        $movie = Movie::create($validated);
        return response()->json($movie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Movie::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $movie = Movie::findOrFail($id);
        
        $validated = $request->validate([
            'titol' => 'sometimes|string|max:255',
            'descripcio' => 'nullable|string',
            'data' => 'sometimes|date',
            'hora' => 'sometimes',
            'preu' => 'sometimes|numeric|min:0',
            'imatge_url' => 'nullable|string',
        ]);

        $movie->update($validated);
        return response()->json($movie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return response()->json(['message' => 'Pel·lícula eliminada']);
    }
}