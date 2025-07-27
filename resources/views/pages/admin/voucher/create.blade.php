@extends('layouts.admin.app')

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dashboard.vouchers.store') }}" method="POST" class="form theme-form basic-form">
                            @csrf

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Voucher Code</h5>
                                        <input class="form-control @error('code') is-invalid @enderror"
                                               type="text"
                                               name="code"
                                               value="{{ old('code') }}"
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
                                                  placeholder="Enter voucher description">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input @error('is_willcard') is-invalid @enderror"
                                                   type="checkbox"
                                                   name="is_willcard"
                                                   id="is_willcard"
                                                   value="1"
                                                {{ old('is_willcard') ? 'checked' : '' }}>
                                            <label class="form-check-label f-w-600" for="is_willcard">
                                                Is Willcard
                                            </label>
                                            @error('is_willcard')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Check this if the voucher is a wildcard voucher</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success me-3">Add</button>
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
