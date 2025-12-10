<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentGateway;
use App\Models\ProductVariant;
use App\Services\Store\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Str;
use App\Mail\OrderConfirmation;
use App\Mail\InstapayOrderConfirmation;
use App\Mail\NewOrderNotification;

class CheckoutController extends Controller
{
    public function index()
    {
        $paymentGateways = PaymentGateway::with('configs')
            ->where('is_active', 1)
            ->get();

        $paypal = $paymentGateways->firstWhere('code', 'paypal');
        $paypalClientId = $paypal
            ? $paypal->getConfigValue('client_id', 'sandbox')
            : null;

        $cart = Session::get('cart', []);
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $product = \App\Models\Product::with(['translations', 'thumbnail'])->find($item['product_id']);

            $variant = isset($item['variant_id'])
                ? ProductVariant::with('images')->find($item['variant_id'])
                : ProductVariant::where('product_id', $item['product_id'])->where('is_primary', true)->first();

            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = null;
        
        // Apply coupon discount logic
        $cartService = app(\App\Services\Store\CartService::class);
        $couponData = $cartService->getAppliedCoupon();
        $discountAmount = 0;
        
        if ($couponData) {
            $discountAmount = $cartService->getDiscountAmount($subtotal);
        }
        
        $total = $subtotal - $discountAmount + ($shipping ?? 0);

        $governorates = \App\Models\Governorate::active()->get();

        // Fetch last order for logged-in users (for auto-fill)
        $lastOrder = null;
        if (Auth::guard('customer')->check()) {
            $lastOrder = Order::where('customer_id', Auth::guard('customer')->id())
                ->latest()
                ->first();
        }

        return view('themes.xylo.checkout', compact('cart', 'subtotal', 'shipping', 'total', 'paymentGateways', 'paypalClientId', 'governorates', 'lastOrder', 'discountAmount', 'couponData'));
    }

    public function process(Request $request)
    {
        $gatewayCode = $request->input('gateway');
        $amount = 100; // you can replace this with cart total

        try {
            $paymentService = PaymentManager::make($gatewayCode, 'sandbox');

            $order = $paymentService->createOrder($amount, 'USD');

            return response()->json([
                'success' => true,
                'gateway' => $gatewayCode,
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment process failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PayPal success callback
     */
    public function paypalSuccess(Request $request, OrderService $orderService)
    {
        $orderId = $request->query('token'); // PayPal returns ?token=ORDER_ID

        try {
            $paypal = PaymentManager::make('paypal', 'sandbox');
            $result = $paypal->captureOrder($orderId);

            if (($result['status'] ?? null) === 'COMPLETED') {

                $order = $orderService->createOrderFromPaypal($result);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment completed & order stored successfully.',
                    'order_id' => $order->id,
                    'details' => $result,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment not completed.',
                'details' => $result,
            ]);
        } catch (\Exception $e) {
            \Log::error('PayPal success error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PayPal cancel callback
     */
    public function paypalCancel()
    {
        return response()->json([
            'success' => false,
            'message' => 'Payment was cancelled by user.',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string|max:255',
            'governorate' => 'required|string',
            'city' => 'required|string',
            'payment_method' => 'required',
            'payment_proof' => 'required_if:payment_method,instapay|image|max:5120', // Max 5MB
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        // Calculate total
        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Create or get customer
        $customer = null;
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
        } else {
            // Create guest customer record
            $customer = \App\Models\Customer::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'phone' => $request->phone,
                    'password' => bcrypt(Str::random(16)), // Random password for guest
                ]
            );
        }

        // Get governorate shipping fee
        $governorate = \App\Models\Governorate::where('name_en', $request->governorate)
            ->orWhere('name_ar', $request->governorate)
            ->first();
            
        $shippingFee = $governorate ? $governorate->shipping_fee : 0;
        
        // Apply coupon discount if present
        $cartService = app(\App\Services\Store\CartService::class);
        $couponData = $cartService->getAppliedCoupon();
        $discountAmount = 0;
        $couponCode = null;
        
        if ($couponData) {
            $discountAmount = $cartService->getDiscountAmount($total);
            $couponCode = $couponData['code'];
        }
        
        $finalTotal = $total - $discountAmount + $shippingFee;

        // Handle Payment Proof Upload
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Store in payment_proofs folder (public disk root is already public/uploads)
            $path = $file->storeAs('payment_proofs', $filename, 'public');
            // Store just the path (payment_proofs/filename)
            // The view will use asset('uploads/' . $path) to generate the URL
            $proofPath = $path;
        }

        // Save Order
        $order = Order::create([
            'customer_id' => $customer->id,
            'shipping_address' => $request->address . ($request->suite ? ', ' . $request->suite : '') . ', ' . $request->city . ', ' . $request->governorate . ', Egypt',
            'billing_address' => $request->address . ($request->suite ? ', ' . $request->suite : '') . ', ' . $request->city . ', ' . $request->governorate . ', Egypt',
            'payment_method' => $request->payment_method,
            'payment_proof' => $proofPath,
            'total_price' => $finalTotal,
            'shipping_cost' => $shippingFee,
            'discount_amount' => $discountAmount,
            'coupon_code' => $couponCode,
            'status' => 'pending',
            'payment_status' => 'pending',
            'order_date' => now(),
        ]);

        // Save Order Items
        foreach ($cart as $item) {
            \App\Models\OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Record coupon usage if a coupon was applied
        if ($couponData) {
            $cartService->recordCouponUsage(
                $order->id, 
                $discountAmount, 
                $customer->id, 
                $request->email
            );
        }

        // Send emails
        try {
            // Send confirmation email to customer
            if ($request->payment_method === 'instapay') {
                \Mail::to($request->email)->send(new InstapayOrderConfirmation($order));
            } else {
                \Mail::to($request->email)->send(new OrderConfirmation($order));
            }
            
            // Send notification email to admin
            $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL', 'admin@example.com'));
            \Mail::to($adminEmail)->send(new NewOrderNotification($order));
        } catch (\Exception $e) {
            // Log email error but don't fail the order
            \Log::error('Order email failed: ' . $e->getMessage());
        }

        // Clear the session cart
        Session::forget('cart');

        return redirect()->route('checkout.success', $order->id);
    }

    public function success($id)
    {
        $order = Order::with(['details.product.translation', 'details.productVariant', 'customer'])->findOrFail($id);

        // Security check: ensure guest can't view other orders (basic check)
        // For logged in users, check ownership
        if (Auth::guard('customer')->check() && $order->customer_id !== Auth::guard('customer')->id()) {
            abort(403);
        }

        return view('themes.xylo.checkout.success', compact('order'));
    }
}
