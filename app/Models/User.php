<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'full_name',
        'address',
        'postal_code',
        'city',
        'country',
        'phone',
        'role',
        'balance_days',
        'days_balance',
        'specialization',
        'specialization_id',
        'skills',
        'experience_level',
        'is_available',
        'working_hours',
        'notes',
        'permissions',
        'portfolio_url',
        'linkedin_url',
        'github_url',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance_days' => 'integer',
        'days_balance' => 'integer',
        'is_available' => 'boolean',
        'working_hours' => 'array',
        'permissions' => 'array',
        'skills' => 'array',
    ];

    // Backwards-compatible accessor/mutator: alias days_balance to balance_days
    public function getDaysBalanceAttribute()
    {
        return $this->attributes['balance_days'] ?? $this->attributes['days_balance'] ?? 0;
    }

    public function setDaysBalanceAttribute($value)
    {
        // write-through to the canonical column if present
        if (array_key_exists('balance_days', $this->attributes)) {
            $this->attributes['balance_days'] = $value;
        } else {
            $this->attributes['days_balance'] = $value;
        }
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function assignedProjects()
    {
        return $this->hasMany(Project::class, 'assigned_developer_id');
    }

    // Role-based methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAdministrator(): bool
    {
        return $this->role === 'administrator';
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    // Developer-specific relationships
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'assigned_administrator_id');
    }

    // Specialization relationship
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    // Team relationships

    // Check if user can access admin panel
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow admin role OR administrator role OR exact admin email
        if (strcasecmp($this->email, 'admin@imhotion.com') === 0) {
            return true;
        }
        return $this->isAdmin() || $this->isAdministrator();
    }
}
