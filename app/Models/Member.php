<?php

namespace App\Models;

use App\Enums\MemberRole;
use App\Enums\MemberStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{

    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $casts = [
        'role' => MemberRole::class,
        'status' => MemberStatus::class,
    ];

    /**
     * The attributes that are mass assignable
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'password',
        'status',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Retrieve all teams associated to the member.
     *
     * @return BelongsToMany
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_member')->withPivot(
            'is_captain',
            'created_at',
            'updated_at'
        );
    }

    /**
     * Get member's captained teams.
     *
     * @return Attribute
     */
    public function captainedTeams(): Attribute
    {
        return Attribute::make(
            get: fn(): Collection => $this->teams()->where('is_captain', true)->get()
        );
    }

    /**
     * Get member's not captained teams.
     *
     * @return Attribute
     */
    public function notCaptainedTeams(): Attribute
    {
        return Attribute::make(
            get: fn(): Collection => $this->teams()->where('is_captain', false)->get()
        );
    }

    /**
     * Return true is the role is the given one.
     *
     * @param MemberRole $role
     *
     * @return bool
     */
    public function isRole(MemberRole $role): bool
    {
        return $this->role === $role;
    }
}
