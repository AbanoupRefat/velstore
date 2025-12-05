<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'shipping_fee',
        'active',
        'delivery_days',
    ];

    protected $casts = [
        'active' => 'boolean',
        'shipping_fee' => 'decimal:2',
    ];

    /**
     * Scope for active governorates
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
