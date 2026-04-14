<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\ConcertSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConcertSeatController extends Controller
{
    /**
     * Display the seats for a specific concert.
     */
    public function index(string $concertId)
    {
        $concert = Concert::findOrFail($concertId);
        return ConcertSeat::where('concert_id', $concertId)
            ->orderBy('row')
            ->orderBy('number')
            ->get();
    }

    /**
     * Update seat status (for testing or future implementation).
     */
    public function update(Request $request, string $id)
    {
        $seat = ConcertSeat::findOrFail($id);
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
            'seat_ids.*' => 'exists:concert_seats,id',
        ]);

        $seatIds = $request->input('seat_ids');
        $userId = $request->user()->id;

        try {
            $purchasedSeats = DB::transaction(function () use ($seatIds, $userId) {
                $seats = ConcertSeat::whereIn('id', $seatIds)
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