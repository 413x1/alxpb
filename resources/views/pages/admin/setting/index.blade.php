@extends('layouts.admin.app')

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dashboard.settings.update') }}" method="POST" class="form theme-form basic-form">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Full Name <span class="text-danger">*</span></h5>
                                        <input class="form-control @error('name') is-invalid @enderror"
                                               type="text"
                                               name="name"
                                               value="{{ old('name', $user->name) }}"
                                               placeholder="Enter full name *"
                                               required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Username <span class="text-danger">*</span></h5>
                                        <input class="form-control @error('username') is-invalid @enderror"
                                               type="text"
                                               name="username"
                                               value="{{ old('username', $user->username) }}"
                                               placeholder="Enter unique username"
                                               required>
                                        @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Email Address <span class="text-danger">*</span></h5>
                                        <input class="form-control @error('email') is-invalid @enderror"
                                               type="email"
                                               name="email"
                                               value="{{ old('email', $user->email) }}"
                                               placeholder="Enter email address"
                                               required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">New Password</h5>
                                        <input class="form-control @error('password') is-invalid @enderror"
                                               type="password"
                                               name="password"
                                               placeholder="Enter new password (leave blank to keep current)"
                                               minlength="8">
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Leave blank if you don't want to change password</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">Update Settings
                                        </button>
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

@section('after-js')
    <script>
        $(document).ready(function() {
            // Auto-hide alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(300);
        });
    </script>
@endsection
