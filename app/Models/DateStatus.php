<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateStatus extends Model
{
    use HasFactory;

    protected $table = 'date_statuses';

    protected $fillable = [
        'date',
        'status',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}