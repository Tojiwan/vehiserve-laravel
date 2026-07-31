<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\LogOptions;

class VehicleRequest extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $table = 'vehicle_requests';

    protected $fillable = [
        'user_ID',
        'request_date',
        'requesting_person',
        'office_college',
        'destination',
        'purpose',
        'departure_date',
        'departure_time',
        'signature',
        'valid_id',
        'num_passengers',
        'vehicle_status',
    ];

    protected $casts = [
        'request_date' => 'date',
        'departure_date' => 'date',
        'departure_time' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID');
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class, 'request_id');
    }

    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['vehicle_status', 'request_date', 'destination'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getMediaCollections(): array
    {
        return ['signatures', 'valid_ids', 'memos'];
    }
}