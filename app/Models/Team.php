<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'team_lead_id',
        'status',
        'specializations',
    ];

    protected $casts = [
        'specializations' => 'array',
    ];

    public function teamLead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot(['role', 'joined_at'])
            ->withTimestamps();
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_teams')
            ->withPivot(['assigned_at'])
            ->withTimestamps();
    }

    public function activeMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('role', '!=', 'inactive');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }

    public function getActiveProjectCountAttribute(): int
    {
        return $this->projects()->whereIn('status', ['in_progress', 'pending'])->count();
    }
}
