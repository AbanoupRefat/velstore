<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'customer_id',
        'order_id',
        'guest_email',
        'discount_applied',
    ];

    protected $casts = [
        'discount_applied' => 'decimal:2',
    ];

    /**
     * Relationship with coupon
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Relationship with customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship with order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
