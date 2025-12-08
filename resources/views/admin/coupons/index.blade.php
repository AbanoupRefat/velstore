@extends('admin.layouts.admin')

@section('title', 'All Coupons')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Promo Codes / Coupons</h4>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Coupon
            </a>
        </div>

        <div class="card">
            <div class="card-header card-header-bg text-white">
                <h6 class="d-flex align-items-center mb-0 dt-heading">All Coupons</h6>
            </div>
            <div class="card-body">
                <table id="coupons-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Usage</th>
                            <th>Min Order</th>
                            <th>Status</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteCouponModal" tabindex="-1" aria-labelledby="deleteCouponModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCouponModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to delete this coupon?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteCoupon">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

@if (session('success'))
<script>
    toastr.success("{{ session('success') }}", "Success", {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 5000
    });
</script>
@endif

<script>
    $(document).ready(function() {
        $('#coupons-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.coupons.data') }}", 
                type: 'POST',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'code', name: 'code' },
                { data: 'discount_display', name: 'discount_display', orderable: false },
                { data: 'usage', name: 'usage', orderable: false },
                { 
                    data: 'min_order_amount', 
                    name: 'min_order_amount',
                    render: function(data) {
                        return data ? parseFloat(data).toFixed(2) + ' EGP' : '-';
                    }
                },
                { data: 'validity', name: 'validity', orderable: false, searchable: false },
                { data: 'status_toggle', name: 'status_toggle', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            pageLength: 10,
            order: [[0, 'desc']]
        });

        // Toggle status
        $(document).on('change', '.toggle-status', function() {
            var couponId = $(this).data('id');
            var isActive = $(this).prop('checked') ? 1 : 0; 
            $.ajax({
                url: '{{ route('admin.coupons.updateStatus') }}', 
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: couponId,
                    status: isActive
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, "Updated");
                    } else {
                        toastr.error(response.message, "Failed");
                    }
                },
                error: function() {
                    toastr.error('Error updating status!', "Error");
                }
            });
        });
    });

    let couponToDeleteId = null;

    function deleteCoupon(id) {
        couponToDeleteId = id;
        $('#deleteCouponModal').modal('show');

        $('#confirmDeleteCoupon').off('click').on('click', function() {
            if (couponToDeleteId !== null) {
                $.ajax({
                    url: '{{ route('admin.coupons.destroy', ':id') }}'.replace(':id', couponToDeleteId),
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#coupons-table').DataTable().ajax.reload();
                            toastr.success(response.message, "Deleted");
                            $('#deleteCouponModal').modal('hide');
                        } else {
                            toastr.error(response.message, "Error");
                        }
                    },
                    error: function() {
                        toastr.error('Error deleting coupon!', "Error");
                        $('#deleteCouponModal').modal('hide');
                    }
                });
            }
        });
    }
</script>
@endsection
