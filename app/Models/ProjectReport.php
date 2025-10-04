<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'filters',
        'data',
        'created_by',
        'generated_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'data' => 'array',
        'generated_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ReportSubscription::class);
    }

    public function isGenerated(): bool
    {
        return !is_null($this->generated_at);
    }

    public function getFormattedDataAttribute(): array
    {
        return $this->data ?? [];
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeGenerated($query)
    {
        return $query->whereNotNull('generated_at');
    }
}
