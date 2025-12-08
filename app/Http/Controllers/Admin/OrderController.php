<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdate;
use App\Models\Order;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }

    public function getData(Request $request)
    {
        $query = Order::query()->latest()->with('customer');

        // Apply status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Apply date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('customer', function (Order $order) {
                if ($order->customer) {
                    return $order->customer->name.' ('.$order->customer->email.')';
                }

                return $order->guest_email ?? 'Guest';
            })
            ->filterColumn('customer', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->whereHas('customer', function($subQ) use ($keyword) {
                        $subQ->where('name', 'like', "%{$keyword}%")
                             ->orWhere('email', 'like', "%{$keyword}%")
                             ->orWhere('phone', 'like', "%{$keyword}%");
                    })
                    ->orWhere('guest_email', 'like', "%{$keyword}%")
                    ->orWhere('id', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('order_date', function (Order $order) {
                return $order->created_at?->format('Y-m-d H:i');
            })
            ->addColumn('total_price', function (Order $order) {
                return number_format((float) $order->total_price, 2);
            })
            ->editColumn('status', function (Order $order) {
                return ucfirst($order->status);
            })
            ->addColumn('receipt', function (Order $order) {
                if ($order->payment_proof) {
                    // Payment proofs are stored as 'payment_proofs/filename' (new)
                    // or 'uploads/payment_proofs/filename' (legacy)
                    $path = $order->payment_proof;
                    if (str_starts_with($path, 'uploads/')) {
                        // Legacy path - strip 'uploads/' prefix
                        $path = substr($path, 8);
                    }
                    $url = asset('uploads/' . $path);
                    
                    $customerName = $order->customer ? $order->customer->name : ($order->guest_email ?? 'Guest');
                    $totalPrice = number_format((float) $order->total_price, 2);
                    
                    return '<button type="button" class="btn btn-sm btn-info text-white view-receipt-btn" 
                        data-url="'.$url.'" 
                        data-order-id="'.$order->id.'"
                        data-customer="'.$customerName.'"
                        data-total="'.$totalPrice.'"
                        data-date="'.$order->created_at?->format('Y-m-d H:i').'">
                        <i class="bi bi-file-earmark-image"></i> View
                    </button>';
                }
                return '<span class="text-muted">N/A</span>';
            })
            ->rawColumns(['action', 'receipt'])
            ->addColumn('action', function (Order $order) {
                return '
                    <a href="'.route('admin.orders.show', $order->id).'" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i> View
                    </a>
                    <span class="border border-danger dt-trash rounded-3 d-inline-block" onclick="deleteOrder('.$order->id.')">
                        <i class="bi bi-trash-fill text-danger"></i>
                    </span>
                ';
            })

            ->rawColumns(['action', 'receipt'])
            ->setRowId('id')
            ->make(true);
    }

    /**
     * Show single order details
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'details.product.translation', 'details.productVariant.translation', 'details.productVariant.attributeValues.attribute'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status and send email notification
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $order = Order::with('customer')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Update order
        $order->update([
            'status' => $newStatus,
            'tracking_number' => $request->tracking_number,
        ]);

        // Send email notification if status changed
        if ($oldStatus !== $newStatus && $order->customer) {
            try {
                Mail::to($order->customer->email)
                    ->send(new OrderStatusUpdate($order, $oldStatus, $newStatus));
            } catch (\Exception $e) {
                \Log::error('Order status email failed: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Order status updated successfully!' . ($oldStatus !== $newStatus ? ' Email sent to customer.' : ''));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => __('cms.orders.deleted_success')]);
    }

    /**
     * Export orders to Excel
     */
    public function export(Request $request)
    {
        try {
            $status = $request->get('status');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            $filename = 'orders_' . date('Y-m-d_His') . '.xlsx';
            $filePath = 'exports/' . $filename;

            // Store the file temporarily
            Excel::store(new OrdersExport($status, $dateFrom, $dateTo), $filePath);

            // Download and delete
            return response()->download(storage_path('app/' . $filePath), $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            \Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
}
