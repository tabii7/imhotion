<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDocument extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'filename',
        'path',
        'size',
        'is_hidden',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
