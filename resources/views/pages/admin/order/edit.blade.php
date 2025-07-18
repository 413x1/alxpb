@extends('layouts.admin.app')

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dashboard.orders.update', $order) }}" method="POST" class="form theme-form basic-form">
                            @csrf
                            @method('PUT')

                            <!-- Order Code (Read-only) -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Order Code</h5>
                                        <input class="form-control"
                                               type="text"
                                               value="{{ $order->code }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer and Product Info (Read-only) -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Customer</h5>
                                        <input class="form-control"
                                               type="text"
                                               value="{{ $order->customer->name ?? 'N/A' }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Product</h5>
                                        <input class="form-control"
                                               type="text"
                                               value="{{ $order->product->name ?? 'N/A' }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Device and Quantity (Read-only) -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Device</h5>
                                        <input class="form-control"
                                               type="text"
                                               value="{{ $order->device->name ?? 'N/A' }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Quantity</h5>
                                        <input class="form-control"
                                               type="number"
                                               value="{{ $order->qty }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Price and Gateway Response (Read-only) -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Total Price</h5>
                                        <input class="form-control"
                                               type="text"
                                               value="Rp {{ number_format($order->total_price, 0, ',', '.') }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Gateway Response</h5>
                                        <textarea class="form-control" rows="3" readonly>{{ $order->gateway_response ? json_encode($order->gateway_response, JSON_PRETTY_PRINT) : 'No response' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Voucher Info (Read-only) -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Voucher Used</h5>
                                        <input class="form-control"
                                               type="text"
                                               value="{{ $order->is_voucher ? 'Yes' : 'No' }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Voucher Code</h5>
                                        <input class="form-control"
                                               type="text"
                                               value="{{ $order->voucher->code ?? 'No voucher' }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Timestamps (Read-only) -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Created At</h5>
                                        <input class="form-control"
                                               type="text"
                                               value="{{ $order->created_at->format('d M Y H:i:s') }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Updated At</h5>
                                        <input class="form-control"
                                               type="text"
                                               value="{{ $order->updated_at->format('d M Y H:i:s') }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Status (Editable) -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Status <span class="text-danger">*</span></h5>
                                        <select class="form-select @error('status') is-invalid @enderror"
                                                name="status"
                                                required>
                                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ old('status', $order->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="failed" {{ old('status', $order->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Active Status (Read-only display) -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Active Status</h5>
                                        <span class="badge {{ $order->is_active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $order->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success me-3">Update Status</button>
                                        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid ends-->
@endsection
