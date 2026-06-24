<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lga extends Model
{
    protected $fillable = ['name', 'state'];

    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
