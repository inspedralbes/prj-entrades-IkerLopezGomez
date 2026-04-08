<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'status',
        'row',
        'number',
        'user_id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}