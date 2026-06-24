<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoiceReport extends Model
{
    protected $fillable = ['user_id', 'audio_path', 'transcript', 'duration'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
