@extends('layouts.admin.app')

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dashboard.vouchers.update', $voucher) }}" method="POST" class="form theme-form basic-form">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Voucher Code</h5>
                                        <input class="form-control @error('code') is-invalid @enderror"
                                               type="text"
                                               name="code"
                                               value="{{ old('code', $voucher->code) }}"
                                               placeholder="Enter unique voucher code">
                                        @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Description</h5>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  name="description"
                                                  rows="4"
                                                  placeholder="Enter voucher description">{{ old('description', $voucher->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if($voucher->is_used)
                                <div class="row">
                                    <div class="col">
                                        <div class="alert alert-info">
                                            <strong>Note:</strong> This voucher has been used on {{ $voucher->used_at->format('d M Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success me-3">Update</button>
                                        <a href="{{ route('dashboard.vouchers.index') }}" class="btn btn-danger">Cancel</a>
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
