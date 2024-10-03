<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trado_id',
        'check_in',
        'check_out',
        'quantity',
        'status'
    ];

    public function trado()
    {
        return $this->belongsTo(Trado::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checkAvailability($trado_id, $check_in, $check_out, $quantity)
    {
        $bookedQuantity = Booking::where('trado_id', $trado_id)
            ->where(function($query) use ($check_in, $check_out) {
                $query->whereBetween('check_in', [$check_in, $check_out])
                      ->orWhereBetween('check_out', [$check_in, $check_out]);
            })
            ->sum('quantity');

        $trado = Trado::find($trado_id);


        $availableQuantity = $trado->available_quantity - $bookedQuantity;

        return $availableQuantity >= $quantity;
    }
}
