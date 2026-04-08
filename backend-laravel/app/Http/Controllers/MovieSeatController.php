<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieSeatController extends Controller
{
    /**
     * Display the seats for a specific movie.
     */
    public function index(string $movieId)
    {
        $movie = Movie::findOrFail($movieId);
        return MovieSeat::where('movie_id', $movieId)
            ->orderBy('row')
            ->orderBy('number')
            ->get();
    }

    /**
     * Update seat status (for testing or future implementation).
     */
    public function update(Request $request, string $id)
    {
        $seat = MovieSeat::findOrFail($id);
        $seat->update($request->only('status'));
        return $seat;
    }

    /**
     * Purchase multiple seats with concurrency control.
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:movie_seats,id',
        ]);

        $seatIds = $request->input('seat_ids');
        $userId = $request->user()->id;

        try {
            $purchasedSeats = DB::transaction(function () use ($seatIds, $userId) {
                $seats = MovieSeat::whereIn('id', $seatIds)
                    ->lockForUpdate()
                    ->get();

                foreach ($seats as $seat) {
                    if ($seat->status == 2) {
                        throw new \Exception("El seient {$seat->row}{$seat->number} ja ha estat comprat.");
                    }
                    $seat->update(['status' => 2, 'user_id' => $userId]);
                }

                return $seats;
            });

            return response()->json([
                'success' => true,
                'message' => 'Compra realitzada amb èxit.',
                'seats' => $purchasedSeats
            ]);

        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}