<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // 1. Calculate Statistics
        $today = now()->startOfDay();

        // Sales Logic
        $todaySales = Order::where('status', 'completed')
            ->where('created_at', '>=', $today)
            ->sum('total_price');

        $totalSales = Order::where('status', 'completed')->sum('total_price');

        // Order Counts
        $completedOrders = Order::where('status', 'completed')->count();
        $totalOrders = Order::count();

        // Entity Counts
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalVendors = Vendor::count();

        // 2. Pack into $data array (Required by View)
        $data = [
            'todaySales' => $todaySales,
            'totalSales' => $totalSales,
            'completedOrders' => $completedOrders,
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalVendors' => $totalVendors,
        ];

        // 3. Recent Orders Widget
        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        // 4. Monthly Sales Chart Data
        $monthlySales = Order::select(
            DB::raw('sum(total_price) as sums'), 
            DB::raw("DATE_FORMAT(created_at,'%M') as months")
        )
        ->where('status', 'completed')
        ->whereYear('created_at', date('Y'))
        ->groupBy('months')
        ->get();

        // 5. Pass $data array + other variables
        return view('admin.dashboard.index', compact('data', 'recentOrders', 'monthlySales'));
    }
}