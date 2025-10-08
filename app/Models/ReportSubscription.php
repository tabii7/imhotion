<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
        'frequency',
        'send_time',
        'is_active',
        'last_sent_at',
    ];

    protected $casts = [
        'send_time' => 'datetime:H:i',
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(ProjectReport::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isDue(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        $lastSent = $this->last_sent_at ?? $this->created_at;

        switch ($this->frequency) {
            case 'daily':
                return $lastSent->diffInDays($now) >= 1;
            case 'weekly':
                return $lastSent->diffInWeeks($now) >= 1;
            case 'monthly':
                return $lastSent->diffInMonths($now) >= 1;
            default:
                return false;
        }
    }
}



