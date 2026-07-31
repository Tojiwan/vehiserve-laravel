<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $table = 'passengers';

    protected $fillable = [
        'request_id',
        'passenger_name',
    ];

    public function vehicleRequest()
    {
        return $this->belongsTo(VehicleRequest::class, 'request_id');
    }
}