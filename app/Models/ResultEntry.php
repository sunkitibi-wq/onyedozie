<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultEntry extends Model
{
    protected $fillable = ['result_id', 'party', 'votes'];

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}
