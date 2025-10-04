<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'developer_id',
        'description',
        'hours_spent',
        'work_date',
        'logged_at',
    ];

    protected $casts = [
        'work_date' => 'date',
        'logged_at' => 'datetime',
        'hours_spent' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}