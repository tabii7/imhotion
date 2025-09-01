<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProjectDocument;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'status',
        'start_date',
        'end_date',
        'delivery_date',
        'day_budget',
        'days_used',
        'notes',

        // status meta
        'pending_note',
        'due_date',
        'completed_at',
        'cancel_reason',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'delivery_date' => 'date',
        'due_date'      => 'date',
        'completed_at'  => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }
}
