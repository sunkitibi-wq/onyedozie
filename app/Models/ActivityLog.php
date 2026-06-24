<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = ['log_name', 'description', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'properties'];

    protected $casts = [
        'properties' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public static function log(string $description, ?Model $subject = null, ?Model $causer = null, ?array $properties = null, ?string $logName = null): self
    {
        return self::create([
            'log_name' => $logName,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'causer_type' => $causer ? get_class($causer) : (auth()->check() ? get_class(auth()->user()) : null),
            'causer_id' => $causer ? $causer->getKey() : (auth()->check() ? auth()->id() : null),
            'properties' => $properties,
        ]);
    }
}
