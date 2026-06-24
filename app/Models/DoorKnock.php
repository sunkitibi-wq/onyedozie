<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoorKnock extends Model
{
    protected $fillable = ['user_id', 'lat', 'lng', 'address_description', 'voter_name', 'outcome', 'notes', 'visited_at'];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
