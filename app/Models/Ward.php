<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    protected $fillable = ['name', 'lga_id'];

    protected $casts = [
        'lga_id' => 'integer',
    ];

    public function lga(): BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }

    public function pollingUnits(): HasMany
    {
        return $this->hasMany(PollingUnit::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
