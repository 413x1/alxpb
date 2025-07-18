@extends('layouts.admin.app')

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dashboard.users.update', $user) }}" method="POST" class="form theme-form basic-form">
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
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Role <span class="text-danger">*</span></h5>
                                        <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                            <option value="">Select Role</option>
                                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">New Password</h5>
                                        <input class="form-control @error('password') is-invalid @enderror"
                                               type="password"
                                               name="password"
                                               placeholder="Leave blank to keep current password">
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Status</h5>
                                        <div class="form-check checkbox checkbox-primary">
                                            <input class="form-check-input"
                                                   id="is_active"
                                                   type="checkbox"
                                                   name="is_active"
                                                   value="1"
                                                {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success me-3">Update</button>
                                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-danger">Cancel</a>
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
