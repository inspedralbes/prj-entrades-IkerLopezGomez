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
        Movie::create([
            'titol' => 'Dune: Part Two',
            'descripcio' => 'L\'epopeia de Paul Atreides continua.',
            'data' => '2026-04-10',
            'hora' => '18:30:00',
            'preu' => 9.50,
            'imatge_url' => 'https://image.tmdb.org/t/p/w500/8b8R8l3899v9D0XLaq9STWHExUM.jpg',
        ]);

        Movie::create([
            'titol' => 'Interstellar',
            'descripcio' => 'Viatge a través del temps i l\'espai.',
            'data' => '2026-04-12',
            'hora' => '20:00:00',
            'preu' => 8.00,
            'imatge_url' => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
        ]);

        Movie::create([
            'titol' => 'Oppenheimer',
            'descripcio' => 'La història de la bomba atòmica.',
            'data' => '2026-04-15',
            'hora' => '21:00:00',
            'preu' => 10.00,
            'imatge_url' => 'https://image.tmdb.org/t/p/w500/8Gxv2S9e6062G3dh290FlmIExvM.jpg',
        ]);

        Movie::create([
            'titol' => 'The Dark Knight',
            'descripcio' => 'Batman s\'enfronta al Joker.',
            'data' => '2026-05-01',
            'hora' => '19:00:00',
            'preu' => 8.50,
            'imatge_url' => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDr9p1v3toZp9PqU6rn.jpg',
        ]);
    }
}