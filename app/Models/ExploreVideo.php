<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExploreVideo extends Model
{
    protected $fillable = [
        'user_id',
        'prompt',
        'thumbnail_url',
        'video_url',
        'duration',
        'resolution',
        'is_public',
        'views_count',
        'likes_count',
        'tags',
        'description',
        'original_filename',
        'file_size',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'tags' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


