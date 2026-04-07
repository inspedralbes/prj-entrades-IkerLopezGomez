<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'titol',
        'descripcio',
        'data',
        'hora',
        'preu',
        'imatge_url',
    ];

    public function getImatgeUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }
        if (strpos($value, 'http') === 0) {
            return $value;
        }
        return url('images/' . str_replace(' ', '%20', $value));
    }

    public function seats()
    {
        return $this->hasMany(MovieSeat::class);
    }
}