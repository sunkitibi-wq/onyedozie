<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    protected $fillable = [
        'content',
        'media_paths',
        'platforms',
        'scheduled_at',
        'status',
        'error_message',
        'engagement_likes',
        'engagement_shares',
        'engagement_reach',
    ];

    protected $casts = [
        'media_paths' => 'array',
        'platforms' => 'array',
        'scheduled_at' => 'datetime',
    ];
}
