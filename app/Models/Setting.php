<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    // Minimal model: allow mass assignment for key/value pairs
    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = false;
}
