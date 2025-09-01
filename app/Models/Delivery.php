<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'note',
        'file_path',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
