<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $primaryKey = 'booking_ID';

    protected $fillable = [
        'user_ID',
        'requesting_personnel',
        'driver_ID',
        'vehicle_ID',
        'num_passengers',
        'destination',
        'status',
        'date',
        'return_date',
    ];

    protected $casts = [
        'date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_ID');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_ID');
    }
}