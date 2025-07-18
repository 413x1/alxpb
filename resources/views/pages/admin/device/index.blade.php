@extends('layouts.admin.app')


@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header card-no-border pb-0">
                            <a href="{{ route('dashboard.devices.create') }}" class="btn btn-info text-light" data-bs-toggle="tooltip" data-bs-original-title="btn btn-info btn-add-device">Add New Device</a>
                            <div class="table-responsive signal-table">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Identifier</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($devices as $device)
                                        <tr>
                                            <th scope="row">{{ $devices->firstItem() + $loop->index }}</th>
                                            <td>{{ $device->name }}</td>
                                            <td>{{ $device->identifier }}</td>
                                            <td>{{ $device->code }}</td>
                                            <td>{{ $device->is_active ? 'Active' : 'Non active' }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.devices.edit', $device->getKey()) }}" class="btn btn-secondary" type="button" data-bs-toggle="tooltip" data-bs-original-title="btn btn-secondary">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                            {{ $devices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection
