<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'attendances';

    protected $fillable = [
        'driver_ID',
        'date',
        'time_in',
        'time_out',
        'status',
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