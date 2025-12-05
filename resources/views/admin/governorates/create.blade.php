@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Add New Governorate</h5>
                </div>
                <div class="card-body">
                    {{-- Error Display --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.governorates.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name (English) <span class="text-danger">*</span></label>
                                <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}" required placeholder="e.g. Cairo">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name (Arabic) <span class="text-danger">*</span></label>
                                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar') }}" required placeholder="مثال: القاهرة">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping Fee (EGP) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="shipping_fee" class="form-control" value="{{ old('shipping_fee') }}" required placeholder="0.00">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Est. Delivery Days <span class="text-danger">*</span></label>
                                <input type="number" name="delivery_days" class="form-control" value="{{ old('delivery_days', 2) }}" min="1" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="activeSwitch" name="active" value="1" checked>
                                <label class="form-check-label" for="activeSwitch">Active Status</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.governorates.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">Save Governorate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection