<?php

namespace Database\Seeders;

use App\Models\Concert;
use Illuminate\Database\Seeder;

class ConcertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $concerts = [
            [
                'titol' => 'Estopa - Gira 25 anys',
                'artista' => 'Estopa',
                'descripcio' => 'Concert al Palau Sant Jordi.',
                'data' => '2026-06-15',
                'hora' => '21:00:00',
                'preu' => 45.00,
                'imatge_url' => 'Estopa.jpeg',
            ],
            [
                'titol' => 'Coldplay - Music of the Spheres',
                'artista' => 'Coldplay',
                'descripcio' => 'Espectacle visual i sonor únic.',
                'data' => '2026-05-20',
                'hora' => '20:30:00',
                'preu' => 85.00,
                'imatge_url' => 'Coldplay.jpeg',
            ],
            [
                'titol' => 'The Tyets - Èpic Solete',
                'artista' => 'The Tyets',
                'descripcio' => 'Música festiva en català.',
                'data' => '2026-04-25',
                'hora' => '22:00:00',
                'preu' => 25.00,
                'imatge_url' => 'thetyets.jpeg',
            ],
            [
                'titol' => 'Bad Gyal - La Joia Tour',
                'artista' => 'Bad Gyal',
                'descripcio' => 'Ritmes de dancehall i reggaeton.',
                'data' => '2026-07-05',
                'hora' => '21:30:00',
                'preu' => 55.00,
                'imatge_url' => 'badgyal.jpeg',
            ],
        ];

        foreach ($concerts as $cData) {
            $concert = Concert::create($cData);
            $basePrice = $cData['preu'];

            // GRADES: U-shaped (L=Left, R=Right, G=General/Bottom)
            // Left (10 seats)
            for ($i = 1; $i <= 10; $i++) {
                $concert->seats()->create(['row' => 'L', 'number' => $i, 'status' => 0, 'preu' => $basePrice * 1.2]);
            }
            // Right (10 seats)
            for ($i = 1; $i <= 10; $i++) {
                $concert->seats()->create(['row' => 'R', 'number' => $i, 'status' => 0, 'preu' => $basePrice * 1.2]);
            }
            // General Bottom (20 seats)
            for ($i = 1; $i <= 20; $i++) {
                $concert->seats()->create(['row' => 'G', 'number' => $i, 'status' => 0, 'preu' => $basePrice * 1.2]);
            }

            // PISTA 1: 75 seats (Premium)
            for ($i = 1; $i <= 75; $i++) {
                $concert->seats()->create(['row' => 'P1', 'number' => $i, 'status' => 0, 'preu' => $basePrice * 1.5]);
            }

            // PISTA 2: 75 seats (Standard)
            for ($i = 1; $i <= 75; $i++) {
                $concert->seats()->create(['row' => 'P2', 'number' => $i, 'status' => 0, 'preu' => $basePrice]);
            }
        }
    }
}