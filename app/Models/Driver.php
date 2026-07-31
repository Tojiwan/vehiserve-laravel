<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Driver extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $primaryKey = 'driver_ID';
    protected $table = 'drivers';

    protected $fillable = [
        'full_name',
        'age',
        'gender',
        'contact',
        'date_joined',
        'status',
        'position',
        'user_ID',
    ];

    protected $casts = [
        'date_joined' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'driver_ID');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'driver_ID');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'driver_ID');
    }

    public function getMediaCollections(): array
    {
        return ['driver_images', 'license'];
    }
}