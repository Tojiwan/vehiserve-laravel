<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'documents';

    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'application/pdf'])
            ->withResponsiveImages();
    }
}