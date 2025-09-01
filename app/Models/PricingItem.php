<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pricing_category_id',
        'title',
        'price',
        'price_unit',
        'duration_years',
        'discount_percent',
        'has_gift_box',
        'has_project_files',
        'has_weekends_included',
        'note',
        'sort',
        'active',
    ];

    protected $casts = [
        'has_gift_box' => 'boolean',
        'has_project_files' => 'boolean',
        'has_weekends_included' => 'boolean',
        'active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(PricingCategory::class, 'pricing_category_id');
    }
}
