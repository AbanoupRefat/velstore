@extends('admin.layouts.admin')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('content')
<div class="container-fluid">
    {{-- Filters Panel --}}
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filters & Export</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label"><strong>Status</strong></label>
                    <select id="statusFilter" class="form-select">
                        <option value="all">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><strong>Date From</strong></label>
                    <input type="date" id="dateFromFilter" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><strong>Date To</strong></label>
                    <input type="date" id="dateToFilter" class="form-control">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button id="applyFilters" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    <button id="clearFilters" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Clear
                    </button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button id="exportExcel" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Export to Excel
                    </button>
                    <small class="text-muted ms-2">
                        <i class="bi bi-info-circle"></i> Export will include current filter selections
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card">
        <div class="card-header card-header-bg text-white">
            <h6>{{ __('cms.orders.title') }}</h6>
        </div>
        <div class="card-body">
        <table id="orders-table" class="table table-bordered mt-4 w-100">
            <thead>
                <tr>
                    <th>{{ __('cms.orders.id') }}</th>
                     <th>{{ __('cms.orders.customer') }}</th>
                    <th>{{ __('cms.orders.order_date') }}</th>
                    <th>{{ __('cms.orders.status') }}</th>
                    <th>{{ __('Receipt') }}</th>
                    <th>{{ __('cms.orders.total_price') }}</th>
                    <th>{{ __('cms.orders.action') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('cms.orders.delete_confirm_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">{{ __('cms.orders.delete_confirm_message') }}</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('cms.orders.delete_cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteOrder">{{ __('cms.orders.delete_button') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@php
    $datatableLang = __('cms.datatables'); 
@endphp

{{-- Session-based Toastr (redirect success messages) --}}
@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}", "{{ __('cms.orders.success') }}", {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 5000
        });
    </script>
@endif
@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}", "Error", {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 5000
        });
    </script>
@endif

<script>
$(document).ready(function () {
    const table = $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.orders.data') }}",
            type: 'POST',
            data: function (d) {
                d._token = "{{ csrf_token() }}";
                d.status = $('#statusFilter').val();
                d.date_from = $('#dateFromFilter').val();
                d.date_to = $('#dateToFilter').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'customer', name: 'customer', orderable: false, searchable: true },
            { data: 'order_date', name: 'order_date', orderable: false, searchable: false },
            { data: 'status', name: 'status' },
            { data: 'receipt', name: 'receipt', orderable: false, searchable: false },
            { data: 'total_price', name: 'total_price', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        pageLength: 10,
        language: @json($datatableLang)
    });

    // Apply Filters Button
    $('#applyFilters').on('click', function() {
        table.ajax.reload();
        toastr.success('Filters applied successfully', 'Success', {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000
        });
    });

    // Clear Filters Button
    $('#clearFilters').on('click', function() {
        $('#statusFilter').val('all');
        $('#dateFromFilter').val('');
        $('#dateToFilter').val('');
        table.ajax.reload();
        toastr.info('Filters cleared', 'Info', {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000
        });
    });

    // Export to Excel Button
    $('#exportExcel').on('click', function() {
        const status = $('#statusFilter').val();
        const dateFrom = $('#dateFromFilter').val();
        const dateTo = $('#dateToFilter').val();
        
        const params = new URLSearchParams();
        if (status && status !== 'all') params.append('status', status);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        const url = "{{ route('admin.orders.export') }}" + (params.toString() ? '?' + params.toString() : '');
        
        toastr.info('Preparing Excel export...', 'Please Wait', {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000
        });
        
        // Use fetch API with blob for proper download
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Export failed');
            
            // Get filename from Content-Disposition header
            const disposition = response.headers.get('Content-Disposition');
            let filename = 'orders_' + new Date().toISOString().slice(0,19).replace(/:/g,'-') + '.xlsx';
            if (disposition && disposition.includes('filename=')) {
                const matches = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                if (matches && matches[1]) {
                    filename = matches[1].replace(/['"]/g, '');
                }
            }
            
            return response.blob().then(blob => ({blob, filename}));
        })
        .then(({blob, filename}) => {
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            toastr.success('Excel file downloaded successfully!', 'Success', {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 3000
            });
        })
        .catch(error => {
            toastr.error('Failed to export orders: ' + error.message, 'Error', {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000
            });
        });
    });

    let orderToDeleteId = null;

    window.deleteOrder = function(id) {
        orderToDeleteId = id;
        $('#deleteOrderModal').modal('show');
    }

    $('#confirmDeleteOrder').on('click', function() {
        if(orderToDeleteId === null) return;

        $.ajax({
            url: '{{ route("admin.orders.destroy", ":id") }}'.replace(':id', orderToDeleteId),
            type: 'DELETE',
            data: { _token: "{{ csrf_token() }}" },
            success: function(res) {
                if(res.success) {
                    table.ajax.reload(null, false);
                    toastr.error(res.message, "{{ __('cms.orders.success') }}", {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 5000
                    });
                    $('#deleteOrderModal').modal('hide');
                    orderToDeleteId = null;
                } else {
                    toastr.error(res.message || 'Failed to delete order', "Error", {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 5000
                    });
                }
            },
            error: function() {
                toastr.error('An error occurred while deleting the order', "Error", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
                $('#deleteOrderModal').modal('hide');
            }
        });
    });

    // Receipt Modal Logic
    $(document).on('click', '.view-receipt-btn', function() {
        const url = $(this).data('url');
        const orderId = $(this).data('order-id');
        const customer = $(this).data('customer');
        const total = $(this).data('total');
        const date = $(this).data('date');

        $('#receiptModalOrderId').text('#' + orderId);
        $('#receiptModalCustomer').text(customer);
        $('#receiptModalTotal').text(total);
        $('#receiptModalDate').text(date);
        $('#receiptModalImage').attr('src', url);
        $('#receiptModalLink').attr('href', url);

        $('#receiptModal').modal('show');
    });
});
</script>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-receipt me-2"></i> Payment Verification
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Sidebar Info -->
                    <div class="col-md-4 bg-light p-4 border-end">
                        <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Order Details</h6>
                        
                        <div class="mb-4">
                            <label class="text-muted small mb-1">Order ID</label>
                            <div class="fw-bold fs-5 text-dark" id="receiptModalOrderId">...</div>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted small mb-1">Customer</label>
                            <div class="fw-bold text-dark" id="receiptModalCustomer">...</div>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted small mb-1">Total Amount</label>
                            <div class="fw-bold text-success fs-5" id="receiptModalTotal">...</div>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted small mb-1">Date</label>
                            <div class="text-dark" id="receiptModalDate">...</div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <a href="#" id="receiptModalLink" target="_blank" class="btn btn-outline-dark w-100 btn-sm">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Open Original
                            </a>
                        </div>
                    </div>

                    <!-- Image Area -->
                    <div class="col-md-8 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center p-4" style="min-height: 400px;">
                        <img src="" id="receiptModalImage" class="img-fluid shadow-sm rounded" style="max-height: 500px; object-fit: contain;" alt="Receipt">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
