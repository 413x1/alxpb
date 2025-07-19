@extends('layouts.admin.app')

@section('after-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/theme/css/vendors/datatables.css') }}">
@endsection

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header card-no-border pb-0">
                            <a href="{{ route('dashboard.users.create') }}" class="btn btn-info text-light" data-bs-toggle="tooltip" data-bs-original-title="btn btn-info btn-add-user">Add New Users</a>
                            <div class="table-responsive signal-table">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <th scope="row">{{ $users->firstItem() + $loop->index }}</th>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>{{ $user->is_active ? 'Active' : 'Non active' }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.users.edit', $user->getKey()) }}" class="btn btn-secondary me-2" type="button" data-bs-toggle="tooltip" data-bs-original-title="Edit User">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <button class="btn btn-danger btn-delete" type="button" data-user-id="{{ $user->getKey() }}" data-user-name="{{ $user->name }}" data-user-email="{{ $user->email }}" data-bs-toggle="tooltip" data-bs-original-title="Delete User">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- Hidden form for delete requests -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('after-js')
    <script src="{{ asset('assets/theme/js/sweetalert/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Handle delete button clicks
            $('.btn-delete').on('click', function() {
                const userId = $(this).data('user-id');
                const userName = $(this).data('user-name');
                const userEmail = $(this).data('user-email');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You want to delete user with name "${userName}"? This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the user.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Set the form action to the delete route
                        const form = $('#delete-form');
                        form.attr('action', `{{ url('dashboard/users') }}/${userId}`);

                        // Submit the form
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
