<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TimeTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'developer_id',
        'tracking_date',
        'start_time',
        'end_time',
        'total_hours',
        'activity_description',
        'activity_type',
        'is_billable',
    ];

    protected $casts = [
        'tracking_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_hours' => 'decimal:2',
        'is_billable' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function getDurationAttribute(): string
    {
        if ($this->start_time && $this->end_time) {
            $start = Carbon::parse($this->start_time);
            $end = Carbon::parse($this->end_time);
            $duration = $start->diffInMinutes($end);
            
            $hours = floor($duration / 60);
            $minutes = $duration % 60;
            
            return sprintf('%d:%02d', $hours, $minutes);
        }
        
        return '0:00';
    }

    public function getActivityTypeColorAttribute(): string
    {
        return match($this->activity_type) {
            'development' => 'blue',
            'testing' => 'green',
            'debugging' => 'red',
            'meeting' => 'purple',
            'documentation' => 'yellow',
            'other' => 'gray',
            default => 'gray'
        };
    }

    public function getActivityTypeIconAttribute(): string
    {
        return match($this->activity_type) {
            'development' => 'fas fa-code',
            'testing' => 'fas fa-bug',
            'debugging' => 'fas fa-tools',
            'meeting' => 'fas fa-users',
            'documentation' => 'fas fa-file-alt',
            'other' => 'fas fa-clock',
            default => 'fas fa-clock'
        };
    }
}