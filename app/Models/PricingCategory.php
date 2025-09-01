<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort',
        'active',
    ];

    public function items()
    {
        return $this->hasMany(PricingItem::class)->orderBy('sort');
    }
}
