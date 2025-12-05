@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Edit Governorate: {{ $governorate->name_en }}</h5>
                </div>
                <div class="card-body">
                    {{-- Error Alerts --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.governorates.update', $governorate->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name (English)</label>
                                <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $governorate->name_en) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name (Arabic)</label>
                                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $governorate->name_ar) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping Fee (EGP)</label>
                                <input type="number" step="0.01" name="shipping_fee" class="form-control" value="{{ old('shipping_fee', $governorate->shipping_fee) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Delivery Days (Estimate)</label>
                                <input type="number" name="delivery_days" class="form-control" value="{{ old('delivery_days', $governorate->delivery_days) }}" min="1" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="activeSwitch" name="active" value="1" {{ $governorate->active ? 'checked' : '' }}>
                                <label class="form-check-label" for="activeSwitch">Active Status</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.governorates.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection