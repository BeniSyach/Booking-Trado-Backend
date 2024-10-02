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
}
