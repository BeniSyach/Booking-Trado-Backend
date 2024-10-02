<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trado extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'capacity',
        'price',
        'available_quantity'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
