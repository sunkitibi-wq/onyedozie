<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollingUnit extends Model
{
    protected $fillable = ['name', 'code', 'ward_id', 'lat', 'lng', 'registered_voters'];

    protected $casts = [
        'ward_id' => 'integer',
        'registered_voters' => 'integer',
        'lat' => 'double',
        'lng' => 'double',
    ];

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
