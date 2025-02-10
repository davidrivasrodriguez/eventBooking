<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'location',
        'available_seats',
        'image'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getAvailableSeats()
    {
        return $this->available_seats - $this->reservations()->count();
    }
}