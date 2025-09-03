<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pricing_item_id',
        'days',
        'amount',
        'currency',
        'status',
    'mollie_payment_id',
    'payment_data',
    'paid_at',
    'credited',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    'payment_data' => 'array',
    'paid_at' => 'datetime',
    'days' => 'integer',
    'credited' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pricingItem()
    {
        return $this->belongsTo(PricingItem::class);
    }
}
