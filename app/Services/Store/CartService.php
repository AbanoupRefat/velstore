<?php

namespace App\Services\Store;

use App\Models\Coupon;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Apply a coupon code with comprehensive validation
     */
    public function applyCoupon($code, $cartTotal = null, $customerId = null, $guestEmail = null)
    {
        // Find coupon case-insensitively
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code.'];
        }

        // Get cart total if not provided
        if ($cartTotal === null) {
            $cartTotal = $this->getCartSubtotal();
        }

        // Use the model's validate method for comprehensive validation
        $validation = $coupon->validate($cartTotal, $customerId, $guestEmail);

        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }

        // Calculate discount amount
        $cart = Session::get('cart', []);
        $discountAmount = $coupon->calculateDiscount($cartTotal, $cart);

        // Store coupon in session
        Session::put('cart_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $coupon->discount,
            'type' => $coupon->type,
            'max_discount' => $coupon->max_discount,
            'calculated_discount' => $discountAmount,
        ]);

        return [
            'success' => true, 
            'message' => 'Coupon applied successfully!', 
            'discount' => $coupon->discount, 
            'type' => $coupon->type,
            'discount_amount' => $discountAmount
        ];
    }

    /**
     * Get cart subtotal (before discount)
     */
    private function getCartSubtotal()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $price = $item['discount_price'] ?? $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 1;
            $total += $price * $quantity;
        }
        
        return $total;
    }

    /**
     * Get cart total with discount applied
     */
    public function getCartTotalWithDiscount($total)
    {
        $couponData = Session::get('cart_coupon');

        if (!$couponData) {
            return $total;
        }

        // Recalculate discount based on current cart total
        $coupon = Coupon::find($couponData['id']);
        
        if (!$coupon) {
            Session::forget('cart_coupon');
            return $total;
        }

        $cart = Session::get('cart', []);
        $discountAmount = $coupon->calculateDiscount($total, $cart);
        
        return max(0, $total - $discountAmount);
    }

    /**
     * Get the current discount amount
     */
    public function getDiscountAmount($total)
    {
        $couponData = Session::get('cart_coupon');

        if (!$couponData) {
            return 0;
        }

        $coupon = Coupon::find($couponData['id']);
        
        if (!$coupon) {
            Session::forget('cart_coupon');
            return 0;
        }

        $cart = Session::get('cart', []);
        return $coupon->calculateDiscount($total, $cart);
    }

    /**
     * Get the applied coupon data from session
     */
    public function getAppliedCoupon()
    {
        return Session::get('cart_coupon');
    }

    /**
     * Remove the applied coupon
     */
    public function removeCoupon()
    {
        Session::forget('cart_coupon');
    }

    /**
     * Record coupon usage after successful order
     */
    public function recordCouponUsage($orderId, $discountApplied, $customerId = null, $guestEmail = null)
    {
        $couponData = Session::get('cart_coupon');

        if (!$couponData) {
            return;
        }

        $coupon = Coupon::find($couponData['id']);

        if ($coupon) {
            $coupon->recordUsage($orderId, $discountApplied, $customerId, $guestEmail);
        }

        // Clear the coupon from session after recording
        Session::forget('cart_coupon');
    }
}
