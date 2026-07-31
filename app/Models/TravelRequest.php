<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TravelRequest extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $table = 'travel_requests';

    protected $fillable = [
        'user_ID',
        'personnel_name',
        'official_station',
        'destination',
        'purpose',
        'inclusive_date',
        'requesting_for',
        'vehicle_request',
        'vehicle_status',
        'signature',
        'valid_id',
        'dean_signature',
        'vp_signature',
        'suc_signature',
        'comment',
    ];

    protected $casts = [
        'inclusive_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID');
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
            ->logOnly(['vehicle_status', 'inclusive_date', 'destination'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getMediaCollections(): array
    {
        return ['signatures', 'valid_ids', 'memos', 'dean_signatures', 'vp_signatures', 'suc_signatures'];
    }
}