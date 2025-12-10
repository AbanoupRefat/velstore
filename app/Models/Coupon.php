<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount',
        'type',
        'expires_at',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'min_order_amount',
        'max_discount',
        'starts_at',
        'is_active',
        'description',
        'buy_qty',
        'get_qty'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'starts_at' => 'datetime',
        'discount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'buy_qty' => 'integer',
        'get_qty' => 'integer',
    ];

    /**
     * Check if coupon has expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && Carbon::parse($this->expires_at)->isPast();
    }

    /**
     * Check if coupon has started
     */
    public function hasStarted(): bool
    {
        return !$this->starts_at || Carbon::parse($this->starts_at)->isPast();
    }

    /**
     * Check if coupon has reached its usage limit
     */
    public function hasReachedLimit(): bool
    {
        return $this->usage_limit !== null && $this->usage_count >= $this->usage_limit;
    }

    /**
     * Check if user has reached their usage limit for this coupon
     */
    public function hasUserReachedLimit($customerId = null, $guestEmail = null): bool
    {
        if ($this->per_user_limit === null) {
            return false;
        }

        $query = $this->usages();
        
        if ($customerId) {
            $query->where('customer_id', $customerId);
        } elseif ($guestEmail) {
            $query->where('guest_email', $guestEmail);
        } else {
            return false; // Cannot check without identifier
        }

        return $query->count() >= $this->per_user_limit;
    }

    /**
     * Calculate discount amount based on cart total
     */
    public function calculateDiscount($cartTotal, $cartItems = null): float
    {
        if ($this->type === 'buy_x_get_y') {
            if (!$cartItems || !$this->buy_qty || !$this->get_qty) {
                return 0;
            }
            return $this->calculateBuyXGetYDiscount($cartItems);
        }

        if ($this->type === 'percentage') {
            $discount = $cartTotal * ($this->discount / 100);
            
            // Apply max discount cap if set
            if ($this->max_discount !== null) {
                $discount = min($discount, $this->max_discount);
            }
            
            return $discount;
        }
        
        // Fixed discount - cannot exceed cart total
        return min($this->discount ?? 0, $cartTotal);
    }

    /**
     * Calculate B3G1 style discount
     */
    private function calculateBuyXGetYDiscount($cartItems): float
    {
        $prices = [];
        
        // Flatten cart items into a list of individual product prices
        foreach ($cartItems as $item) {
            $price = $item['price']; // Handle discount_price in controller/service if needed
            $qty = $item['quantity'];
            for ($i = 0; $i < $qty; $i++) {
                $prices[] = $price;
            }
        }

        // Sort prices descending (expensive first)
        rsort($prices);

        $totalDiscount = 0;
        $groupSize = $this->buy_qty + $this->get_qty;
        
        // Logic: For every group of (X+Y), the last Y items are free
        // e.g. Buy 3 Get 1 (Size 4). Indices 0,1,2 Paid. Index 3 Free.
        // e.g. Buy 1 Get 1 (Size 2). Index 0 Paid. Index 1 Free.
        
        foreach ($prices as $index => $price) {
            // Check if this item is in the "Get" position
            // 0-based index. 
            // Position in group: ($index + 1) % $groupSize
            // If remainder is 0, or > buy_qty (if get_qty > 1)
            // Example B3G1 (Group 4). Index 3 (Pos 4) is free. 4 % 4 == 0.
            // Example B1G1 (Group 2). Index 1 (Pos 2) is free. 2 % 2 == 0.
            // What if B2G2 (Group 4). Indices 2, 3 free. 
            // Pos 3 (%4=3), Pos 4 (%4=0).
            // Logic: Pos in group = ($index % $groupSize) + 1.
            // If Pos > $buy_qty, it is free.
            
            $posInGroup = ($index % $groupSize) + 1;
            
            if ($posInGroup > $this->buy_qty) {
                $totalDiscount += $price;
            }
        }

        return $totalDiscount;
    }

    /**
     * Validate coupon can be used with given parameters
     */
    public function validate($cartTotal, $customerId = null, $guestEmail = null): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'This coupon is not active.'];
        }

        if (!$this->hasStarted()) {
            return ['valid' => false, 'message' => 'This coupon is not yet active.'];
        }

        if ($this->isExpired()) {
            return ['valid' => false, 'message' => 'This coupon has expired.'];
        }

        if ($this->hasReachedLimit()) {
            return ['valid' => false, 'message' => 'This coupon has reached its maximum usage limit.'];
        }

        if ($this->hasUserReachedLimit($customerId, $guestEmail)) {
            return ['valid' => false, 'message' => 'You have already used this coupon.'];
        }

        if ($this->min_order_amount !== null && $cartTotal < $this->min_order_amount) {
            return [
                'valid' => false, 
                'message' => 'Minimum order amount for this coupon is ' . number_format($this->min_order_amount, 2) . ' EGP.'
            ];
        }

        return ['valid' => true, 'message' => 'Coupon is valid.'];
    }

    /**
     * Record usage of this coupon
     */
    public function recordUsage($orderId, $discountApplied, $customerId = null, $guestEmail = null): void
    {
        $this->usages()->create([
            'order_id' => $orderId,
            'customer_id' => $customerId,
            'guest_email' => $guestEmail,
            'discount_applied' => $discountApplied,
        ]);

        $this->increment('usage_count');
    }

    /**
     * Relationship with coupon usages
     */
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }
}
