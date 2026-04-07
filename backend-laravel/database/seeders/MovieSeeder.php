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
            'preu' => 8.00,
            'imatge_url' => 'dune.jpeg',
        ]);

        Movie::create([
            'titol' => 'Interstellar',
            'descripcio' => 'Viatge a través del temps i l\'espai.',
            'data' => '2026-04-12',
            'hora' => '20:00:00',
            'preu' => 8.00,
            'imatge_url' => 'interstellar.jpg',
        ]);

        Movie::create([
            'titol' => 'Oppenheimer',
            'descripcio' => 'La història de la bomba atòmica.',
            'data' => '2026-04-15',
            'hora' => '21:00:00',
            'preu' => 8.00,
            'imatge_url' => 'oppenheimer.png',
        ]);

        Movie::create([
            'titol' => 'The Dark Knight',
            'descripcio' => 'Batman s\'enfronta al Joker.',
            'data' => '2026-05-01',
            'hora' => '19:00:00',
            'preu' => 8.00,
            'imatge_url' => 'the dark knight.jpeg',
        ]);
    }
}