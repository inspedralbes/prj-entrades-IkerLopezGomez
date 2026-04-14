<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcertSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'concert_id',
        'status',
        'row',
        'number',
        'preu',
        'user_id',
    ];

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }
}