<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'icon',
        'skills',
        'tools',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'skills' => 'array',
        'tools' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get users with this specialization
     */
    public function users()
    {
        return $this->hasMany(User::class, 'specialization_id');
    }

    /**
     * Scope for active specializations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get formatted skills as string
     */
    public function getSkillsStringAttribute()
    {
        return is_array($this->skills) ? implode(', ', $this->skills) : '';
    }

    /**
     * Get formatted tools as string
     */
    public function getToolsStringAttribute()
    {
        return is_array($this->tools) ? implode(', ', $this->tools) : '';
    }

    /**
     * Get category display name
     */
    public function getCategoryDisplayAttribute()
    {
        $categories = [
            'developers' => 'Developers / Engineers',
            'designers' => 'Designers / Creatives',
            'management' => 'Management / Coordination',
            'specialized_it' => 'Specialized IT Roles',
            'support' => 'Support / Cross-Functional',
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }
}