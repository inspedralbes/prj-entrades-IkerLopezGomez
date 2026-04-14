<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        var_dump("Seeding movies...");

        $movies = [
            [
                'titol' => 'Dune: Part Two',
                'descripcio' => 'L\'epopeia de Paul Atreides continua.',
                'data' => '2026-04-10',
                'hora' => '18:30:00',
                'preu' => 8.00,
                'imatge_url' => 'dune.jpeg',
            ],
            [
                'titol' => 'Interstellar',
                'descripcio' => 'Viatge a través del temps i l\'espai.',
                'data' => '2026-04-12',
                'hora' => '20:00:00',
                'preu' => 8.00,
                'imatge_url' => 'interstellar.jpg',
            ],
            [
                'titol' => 'Oppenheimer',
                'descripcio' => 'La història de la bomba atòmica.',
                'data' => '2026-04-15',
                'hora' => '21:00:00',
                'preu' => 8.00,
                'imatge_url' => 'oppenheimer.png',
            ],
            [
                'titol' => 'The Dark Knight',
                'descripcio' => 'Batman s\'enfronta al Joker.',
                'data' => '2026-05-01',
                'hora' => '19:00:00',
                'preu' => 8.00,
                'imatge_url' => 'the dark knight.jpeg',
            ]
        ];

        foreach ($movies as $movieData) {
            $movie = Movie::create($movieData);

            // Generar seients 8x8
            $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            foreach ($rows as $row) {
                for ($num = 1; $num <= 8; $num++) {
                    \App\Models\MovieSeat::create([
                        'movie_id' => $movie->id,
                        'row' => $row,
                        'number' => $num,
                        'status' => 0
                    ]);
                }
            }
        }
    }
}