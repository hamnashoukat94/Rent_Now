<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_name',
        'location',
        'booking_date',
        'hours',
        'total_amount',
        'payment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
