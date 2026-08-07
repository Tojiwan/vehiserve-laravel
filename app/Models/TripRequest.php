<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class TripRequest extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $table = 'trip_requests';

    protected $fillable = [
        'user_ID',
        // Travel fields
        'personnel_name',
        'official_station',
        'destination',
        'purpose',
        'inclusive_date',
        'requesting_for',
        // Vehicle fields
        'departure_date',
        'departure_time',
        'return_date',
        'num_passengers',
        'vehicle_ID',
        'driver_ID',
        'status',
    ];

    protected $casts = [
        'inclusive_date' => 'date',
        'departure_date' => 'date',
        'return_date' => 'date',
        'departure_time' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_ID');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_ID');
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
            ->logOnly(['status', 'inclusive_date', 'departure_date', 'destination'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function getMediaCollections(): array
    {
        return ['signatures', 'valid_ids', 'memos'];
    }
}