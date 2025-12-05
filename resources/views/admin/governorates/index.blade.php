@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Governorates & Shipping</h3>
        <a href="{{ route('admin.governorates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                {{-- Standard Table --}}
                <table id="governorates-table" class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>English Name</th>
                            <th>Arabic Name</th>
                            <th>Shipping Fee</th>
                            <th>Est. Days</th>
                            <th>Status</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($governorates as $key => $gov)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-bold">{{ $gov->name_en }}</td>
                            <td>{{ $gov->name_ar }}</td>
                            <td>{{ number_format($gov->shipping_fee, 2) }} EGP</td>
                            <td>{{ $gov->delivery_days }} Days</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox" 
                                           data-id="{{ $gov->id }}" 
                                           {{ $gov->active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.governorates.edit', $gov->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $gov->id }}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <h5 class="text-muted">No Governorates Found</h5>
                                <p>Click "Add New" to create one.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Initialize standard DataTables for client-side sorting/search --}}
<script>
    $(document).ready(function() {
        // Initialize DataTable on the existing static table
        var table = $('#governorates-table').DataTable({
            "order": [], // Disable initial sort to keep controller order
            "columnDefs": [
                { "orderable": false, "targets": [5, 6] } // Disable sorting on Status/Action columns
            ]
        });

        // Toggle Status Logic
        $(document).on('change', '.toggle-status', function() {
            var id = $(this).data('id');
            var url = "{{ url('admin/governorates') }}/" + id + "/toggle-status";
            
            $.post(url, {
                _token: "{{ csrf_token() }}"
            }).fail(function() {
                alert('Error updating status. Please try again.');
            });
        });

        // Delete Logic
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = "{{ url('admin/governorates') }}/" + id;

            if(confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        if(res.success) {
                            location.reload(); // Reload page to reflect changes
                        } else {
                            alert('Error: ' + res.message);
                        }
                    },
                    error: function(err) {
                        alert('System Error: Could not delete.');
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection