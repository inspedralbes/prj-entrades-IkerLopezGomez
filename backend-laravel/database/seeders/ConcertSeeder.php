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
        var_dump("Seeding concerts...");
        Concert::create([
            'titol' => 'Estopa - Gira 25 anys',
            'artista' => 'Estopa',
            'descripcio' => 'Concert al Palau Sant Jordi.',
            'data' => '2026-06-15',
            'hora' => '21:00:00',
            'preu' => 45.00,
            'imatge_url' => 'https://www.estopa.com/wp-content/uploads/2023/11/ESTOPA-25-ANOS.jpg',
        ]);

        Concert::create([
            'titol' => 'Coldplay - Music of the Spheres',
            'artista' => 'Coldplay',
            'descripcio' => 'Espectacle visual i sonor únic.',
            'data' => '2026-05-20',
            'hora' => '20:30:00',
            'preu' => 85.00,
            'imatge_url' => 'https://images.sk-static.com/images/media/profile_images/artists/432128/huge_avatar',
        ]);

        Concert::create([
            'titol' => 'The Tyets - Èpic Solete',
            'artista' => 'The Tyets',
            'descripcio' => 'Música festiva en català.',
            'data' => '2026-04-25',
            'hora' => '22:00:00',
            'preu' => 25.00,
            'imatge_url' => 'https://thetyets.com/wp-content/uploads/2023/03/the-tyets-epic-solete.jpg',
        ]);

        Concert::create([
            'titol' => 'Bad Gyal - La Joia Tour',
            'artista' => 'Bad Gyal',
            'descripcio' => 'Ritmes de dancehall i reggaeton.',
            'data' => '2026-07-05',
            'hora' => '21:30:00',
            'preu' => 55.00,
            'imatge_url' => 'https://okdiario.com/look/img/2023/11/27/bad-gyal.jpg',
        ]);
    }
}