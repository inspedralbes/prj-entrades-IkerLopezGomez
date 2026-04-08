<?php

namespace App\Http\Controllers;

use App\Models\MovieSeat;
use App\Models\ConcertSeat;
use App\Models\Movie;
use App\Models\Concert;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $movieSeats = MovieSeat::where('user_id', $userId)
            ->where('status', 2)
            ->with('movie')
            ->get()
            ->map(function ($seat) {
                return [
                    'type' => 'movie',
                    'id' => $seat->id,
                    'event' => $seat->movie->titol,
                    'date' => $seat->movie->data,
                    'row' => $seat->row,
                    'number' => $seat->number,
                    'purchased_at' => $seat->updated_at,
                ];
            });

        $concertSeats = ConcertSeat::where('user_id', $userId)
            ->where('status', 2)
            ->with('concert')
            ->get()
            ->map(function ($seat) {
                return [
                    'type' => 'concert',
                    'id' => $seat->id,
                    'event' => $seat->concert->titol,
                    'date' => $seat->concert->data,
                    'row' => $seat->row,
                    'number' => $seat->number,
                    'purchased_at' => $seat->updated_at,
                ];
            });

        $tickets = $movieSeats->concat($concertSeats)->sortByDesc('purchased_at')->values();

        return response()->json([
            'tickets' => $tickets,
        ]);
    }
}
