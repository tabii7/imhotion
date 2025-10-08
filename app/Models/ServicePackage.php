<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'service_type',
        'time_unit',
        'price_per_unit',
        'min_units',
        'max_units',
        'features',
        'specializations',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'features' => 'array',
        'specializations' => 'array',
        'is_active' => 'boolean',
        'min_units' => 'integer',
        'max_units' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the service type display name
     */
    public function getServiceTypeDisplayAttribute()
    {
        return match($this->service_type) {
            'ui_ux_design' => 'UI/UX Design',
            'web_development' => 'Web Development',
            'backend_development' => 'Backend Development',
            'mobile_development' => 'Mobile Development',
            'database_development' => 'Database Development',
            'full_stack' => 'Full Stack Development',
            'consulting' => 'Consulting',
            default => ucfirst(str_replace('_', ' ', $this->service_type))
        };
    }

    /**
     * Get the time unit display name
     */
    public function getTimeUnitDisplayAttribute()
    {
        return match($this->time_unit) {
            'hour' => 'Hour',
            'day' => 'Day',
            'week' => 'Week',
            'month' => 'Month',
            default => ucfirst($this->time_unit)
        };
    }

    /**
     * Calculate total price for given units
     */
    public function calculatePrice($units)
    {
        return $this->price_per_unit * $units;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '€' . number_format($this->price_per_unit, 2);
    }

    /**
     * Scope for active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for service type
     */
    public function scopeServiceType($query, $type)
    {
        return $query->where('service_type', $type);
    }

    /**
     * Scope for time unit
     */
    public function scopeTimeUnit($query, $unit)
    {
        return $query->where('time_unit', $unit);
    }

    /**
     * Get compatible developers for this package
     */
    public function getCompatibleDevelopers()
    {
        if (empty($this->specializations)) {
            return User::where('role', 'developer')->where('is_available', true)->get();
        }

        return User::where('role', 'developer')
            ->where('is_available', true)
            ->whereIn('specialization_id', $this->specializations)
            ->get();
    }
}