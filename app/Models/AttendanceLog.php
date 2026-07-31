<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $table = 'attendance_logs';

    protected $fillable = [
        'driver_ID',
        'date',
        'time_in',
        'time_out',
        'status',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'time',
        'time_out' => 'time',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_ID');
    }
}