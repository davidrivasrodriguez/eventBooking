<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'email',
        'phone',
        'event_id',
        'seat_number'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}