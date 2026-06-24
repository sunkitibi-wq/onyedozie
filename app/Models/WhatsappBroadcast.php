<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappBroadcast extends Model
{
    protected $fillable = [
        'message',
        'media_path',
        'audience_type',
        'audience_filter',
        'status',
        'sent_count',
        'delivered_count',
        'failed_count',
    ];

    protected $casts = [
        'audience_filter' => 'array',
    ];
}
