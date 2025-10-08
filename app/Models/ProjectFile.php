<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'uploaded_by',
        'progress_id',
        'original_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'description',
        'is_public',
        'google_drive_file_id',
        'google_drive_url',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_public' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function progress(): BelongsTo
    {
        return $this->belongsTo(ProjectProgress::class);
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileIconAttribute(): string
    {
        return match($this->file_type) {
            'image' => 'fas fa-image',
            'document' => 'fas fa-file-alt',
            'code' => 'fas fa-code',
            'pdf' => 'fas fa-file-pdf',
            'video' => 'fas fa-video',
            default => 'fas fa-file'
        };
    }
}