<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vehicle extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $primaryKey = 'vehicle_ID';
    protected $table = 'vehicles';

    protected $fillable = [
        'vehicle_name',
        'plate_number',
        'model',
        'year',
        'capacity',
        'status',
        'type',
        'description',
        'date_acquired',
        'date_last_maintained',
    ];

    protected $casts = [
        'date_acquired' => 'date',
        'date_last_maintained' => 'date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vehicle_ID');
    }

    public function getMediaCollections(): array
    {
        return ['vehicle_images', 'documents'];
    }
}