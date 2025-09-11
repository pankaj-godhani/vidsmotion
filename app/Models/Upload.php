<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Upload extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'result_data',
        'error_message',
        'processed_at',
    ];

    protected $casts = [
        'result_data' => 'array',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
