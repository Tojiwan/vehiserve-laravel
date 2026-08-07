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
        'request_date',
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
        'request_date' => 'date',
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

    public function outcomeLabel(): ?string
    {
        return \App\Enums\TripRequestStatus::tryFrom($this->status)?->outcomeLabel();
    }

    public function progressSteps(): array
    {
        $approvals = $this->approvals->keyBy('role');

        $roleSteps = $this->inclusive_date
            ? [
                ['label' => 'Dean', 'role' => 'Dean'],
                ['label' => 'VP', 'role' => 'Vice President'],
                ['label' => 'SUC', 'role' => 'SUC President'],
                ['label' => 'MP', 'role' => 'Motor Pool'],
            ]
            : [
                ['label' => 'MP', 'role' => 'Motor Pool'],
                ['label' => 'Dean', 'role' => 'Dean'],
                ['label' => 'VP', 'role' => 'Vice President'],
                ['label' => 'SUC', 'role' => 'SUC President'],
            ];

        $steps = collect($roleSteps)->map(function ($step) use ($approvals) {
            return [
                'label' => $step['label'],
                'status' => $approvals->get($step['role'])?->status ?? 'Waiting',
            ];
        })->all();

        if ($this->inclusive_date) {
            $steps[3]['label'] = $this->outcomeLabel() ?? 'MP';
        } else {
            $steps[] = [
                'label' => $this->outcomeLabel() ?? 'Final MP',
                'status' => match (true) {
                    in_array($this->status, ['Vehicle Assigned', 'Completed']) => 'Approved',
                    in_array($this->status, ['No Vehicle Available', 'Rejected']) || str_starts_with($this->status, 'Rejected by') => 'Rejected',
                    $this->status === 'Cancelled by User' => 'Cancelled',
                    $this->status === 'Pending Final MP Approval' => 'Pending',
                    default => 'Waiting',
                },
            ];
        }

        return $steps;
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