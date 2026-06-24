<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string $phone
 * @property int|null $lga_id
 * @property int|null $ward_id
 * @property int|null $polling_unit_id
 * @property string $status
 * @property string|null $device_token
 * @property Carbon|null $last_seen_at
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'phone', 'phone_verified_at', 'passport_path', 'occupation', 'lga_id', 'ward_id', 'polling_unit_id', 'status', 'device_token', 'last_seen_at'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable, HasApiTokens, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
            'lga_id' => 'integer',
            'ward_id' => 'integer',
            'polling_unit_id' => 'integer',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function pollingUnit()
    {
        return $this->belongsTo(PollingUnit::class);
    }

    public function doorKnocks()
    {
        return $this->hasMany(DoorKnock::class);
    }

    public function agentLocations()
    {
        return $this->hasMany(AgentLocation::class);
    }

    public function latestAgentLocation()
    {
        return $this->hasOne(AgentLocation::class)->latestOfMany('recorded_at');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function leaderboardPoints()
    {
        return $this->hasMany(LeaderboardPoint::class);
    }

    public function getPointsAttribute(): int
    {
        return $this->leaderboardPoints()->sum('points');
    }
}
