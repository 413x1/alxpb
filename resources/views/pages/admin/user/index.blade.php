@extends('layouts.admin.app')


@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header card-no-border pb-0">
                            <a href="{{ route('dashboard.users.create') }}" class="btn btn-info text-light" data-bs-toggle="tooltip" data-bs-original-title="btn btn-info btn-add-device">Add New Users</a>
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
                                                <a href="{{ route('dashboard.users.edit', $user->getKey()) }}" class="btn btn-secondary" type="button" data-bs-toggle="tooltip" data-bs-original-title="btn btn-secondary">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
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
@endsection
