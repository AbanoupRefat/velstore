<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons
     */
    public function index()
    {
        return view('admin.coupons.index');
    }

    /**
     * Get coupons data for DataTables
     */
    public function getData(Request $request)
    {
        $coupons = Coupon::query()->latest();

        return DataTables::of($coupons)
            ->addColumn('discount_display', function ($coupon) {
                if ($coupon->type === 'buy_x_get_y') {
                    return "Buy {$coupon->buy_qty} Get {$coupon->get_qty} Free";
                }
                if ($coupon->type === 'percentage') {
                    return $coupon->discount . '%';
                }
                return number_format($coupon->discount, 2) . ' EGP';
            })
            ->addColumn('usage', function ($coupon) {
                $used = $coupon->usage_count;
                $limit = $coupon->usage_limit ?? '∞';
                return "{$used} / {$limit}";
            })
            ->addColumn('validity', function ($coupon) {
                $now = now();
                
                if (!$coupon->is_active) {
                    return '<span class="badge bg-secondary">Inactive</span>';
                }
                
                if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
                    return '<span class="badge bg-info">Upcoming</span>';
                }
                
                if ($coupon->expires_at && $coupon->expires_at->isPast()) {
                    return '<span class="badge bg-danger">Expired</span>';
                }
                
                if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
                    return '<span class="badge bg-warning">Limit Reached</span>';
                }
                
                return '<span class="badge bg-success">Active</span>';
            })
            ->addColumn('status_toggle', function ($coupon) {
                $checked = $coupon->is_active ? 'checked' : '';
                return '<label class="switch">
                            <input type="checkbox" class="toggle-status" data-id="'.$coupon->id.'" '.$checked.'>
                            <span class="slider round"></span>
                        </label>';
            })
            ->addColumn('action', function ($coupon) {
                $editUrl = route('admin.coupons.edit', $coupon->id);
                return '
                    <span class="border border-edit dt-trash rounded-3 d-inline-block">
                        <a href="'.$editUrl.'"><i class="bi bi-pencil-fill pencil-edit-color"></i></a>
                    </span>
                    <span class="border border-danger dt-trash rounded-3 d-inline-block" onclick="deleteCoupon('.$coupon->id.')">
                        <i class="bi bi-trash-fill text-danger"></i>
                    </span>';
            })
            ->rawColumns(['validity', 'status_toggle', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new coupon
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount' => 'nullable|numeric|min:0|required_unless:type,buy_x_get_y',
            'type' => 'required|in:percentage,fixed,buy_x_get_y',
            'description' => 'nullable|string|max:500',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'buy_qty' => 'nullable|required_if:type,buy_x_get_y|integer|min:1',
            'get_qty' => 'nullable|required_if:type,buy_x_get_y|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Ensure code is uppercase
        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully!');
    }

    /**
     * Show the form for editing the specified coupon
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'discount' => 'nullable|numeric|min:0|required_unless:type,buy_x_get_y',
            'type' => 'required|in:percentage,fixed,buy_x_get_y',
            'description' => 'nullable|string|max:500',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'buy_qty' => 'nullable|required_if:type,buy_x_get_y|integer|min:1',
            'get_qty' => 'nullable|required_if:type,buy_x_get_y|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Ensure code is uppercase
        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully!');
    }

    /**
     * Remove the specified coupon
     */
    public function destroy($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();

            return response()->json([
                'success' => true,
                'message' => 'Coupon deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting coupon.'
            ]);
        }
    }

    /**
     * Toggle coupon active status
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:coupons,id',
            'status' => 'required|boolean'
        ]);

        $coupon = Coupon::findOrFail($request->id);
        $coupon->is_active = $request->status;
        $coupon->save();

        return response()->json([
            'success' => true,
            'message' => 'Coupon status updated successfully!'
        ]);
    }
}
