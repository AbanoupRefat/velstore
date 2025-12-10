@extends('admin.layouts.admin')

@section('title', 'Create Coupon')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Create New Coupon</h4>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-header card-header-bg text-white">
                <h6 class="mb-0">Coupon Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Code -->
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" 
                                   placeholder="e.g. SAVE20" style="text-transform: uppercase;">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Code will be converted to uppercase automatically</small>
                        </div>

                        <!-- Type -->
                        <div class="col-md-3 mb-3">
                            <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" onchange="toggleTypeFields()">
                                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (EGP)</option>
                                <option value="buy_x_get_y" {{ old('type') == 'buy_x_get_y' ? 'selected' : '' }}>Buy X Get Y Free</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Discount Value -->
                        <div class="col-md-3 mb-3" id="discount_wrapper">
                            <label for="discount" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('discount') is-invalid @enderror" 
                                   id="discount" name="discount" value="{{ old('discount') }}" placeholder="e.g. 20">
                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buy Qty (X) -->
                        <div class="col-md-3 mb-3 d-none" id="buy_qty_wrapper">
                            <label for="buy_qty" class="form-label">Buy Quantity (X) <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control @error('buy_qty') is-invalid @enderror" 
                                   id="buy_qty" name="buy_qty" value="{{ old('buy_qty') }}" placeholder="e.g. 3">
                            @error('buy_qty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Get Qty (Y) -->
                        <div class="col-md-3 mb-3 d-none" id="get_qty_wrapper">
                            <label for="get_qty" class="form-label">Get Quantity (Y) <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control @error('get_qty') is-invalid @enderror" 
                                   id="get_qty" name="get_qty" value="{{ old('get_qty') }}" placeholder="e.g. 1">
                            @error('get_qty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <script>
                        function toggleTypeFields() {
                            const type = document.getElementById('type').value;
                            const discountWrapper = document.getElementById('discount_wrapper');
                            const buyQtyWrapper = document.getElementById('buy_qty_wrapper');
                            const getQtyWrapper = document.getElementById('get_qty_wrapper');

                            if (type === 'buy_x_get_y') {
                                discountWrapper.classList.add('d-none');
                                buyQtyWrapper.classList.remove('d-none');
                                getQtyWrapper.classList.remove('d-none');
                            } else {
                                discountWrapper.classList.remove('d-none');
                                buyQtyWrapper.classList.add('d-none');
                                getQtyWrapper.classList.add('d-none');
                            }
                        }
                        
                        // Run on load
                        document.addEventListener('DOMContentLoaded', toggleTypeFields);
                    </script>

                    <div class="row">
                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="2" 
                                      placeholder="Optional description for internal use">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">Usage Limits</h6>

                    <div class="row">
                        <!-- Usage Limit -->
                        <div class="col-md-3 mb-3">
                            <label for="usage_limit" class="form-label">Total Usage Limit</label>
                            <input type="number" min="1" class="form-control @error('usage_limit') is-invalid @enderror" 
                                   id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" 
                                   placeholder="Unlimited">
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty for unlimited</small>
                        </div>

                        <!-- Per User Limit -->
                        <div class="col-md-3 mb-3">
                            <label for="per_user_limit" class="form-label">Per User Limit</label>
                            <input type="number" min="1" class="form-control @error('per_user_limit') is-invalid @enderror" 
                                   id="per_user_limit" name="per_user_limit" value="{{ old('per_user_limit', 1) }}" 
                                   placeholder="1">
                            @error('per_user_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Minimum Order Amount -->
                        <div class="col-md-3 mb-3">
                            <label for="min_order_amount" class="form-label">Min Order Amount (EGP)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('min_order_amount') is-invalid @enderror" 
                                   id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') }}" 
                                   placeholder="No minimum">
                            @error('min_order_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Max Discount -->
                        <div class="col-md-3 mb-3">
                            <label for="max_discount" class="form-label">Max Discount (EGP)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('max_discount') is-invalid @enderror" 
                                   id="max_discount" name="max_discount" value="{{ old('max_discount') }}" 
                                   placeholder="No limit">
                            @error('max_discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">For percentage discounts only</small>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">Validity Period</h6>

                    <div class="row">
                        <!-- Starts At -->
                        <div class="col-md-4 mb-3">
                            <label for="starts_at" class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                                   id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty to start immediately</small>
                        </div>

                        <!-- Expires At -->
                        <div class="col-md-4 mb-3">
                            <label for="expires_at" class="form-label">Expiry Date</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty for no expiry</small>
                        </div>

                        <!-- Is Active -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Create Coupon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
