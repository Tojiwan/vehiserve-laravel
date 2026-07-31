<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Approval extends Model
{
    use HasFactory;

    protected $table = 'approvals';

    protected $fillable = [
        'approvable_type',
        'approvable_id',
        'user_ID',
        'role',
        'status',
        'comment',
        'signature',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID');
    }
}