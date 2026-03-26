<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;

    protected $fillable = [
        'titol',
        'artista',
        'descripcio',
        'data',
        'hora',
        'preu',
        'imatge_url',
    ];

    public function seats()
    {
        return $this->hasMany(ConcertSeat::class);
    }
}