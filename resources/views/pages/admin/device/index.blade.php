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
                            <a href="{{ route('dashboard.devices.create') }}" class="btn btn-info text-light" data-bs-toggle="tooltip" data-bs-original-title="btn btn-info btn-add-device">Add New Device</a>
                            <div class="table-responsive signal-table">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Identifier</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Api Url</th>
                                        <th scope="col">Trigger Url</th>
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
                                            <td>{{ $device->api_url }}</td>
                                            <td>{{ $device->trigger_url }}</td>
                                            <td>{{ $device->is_active ? 'Active' : 'Non active' }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.devices.edit', $device->getKey()) }}" class="btn btn-secondary me-2" type="button" data-bs-toggle="tooltip" data-bs-original-title="Edit Device">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <button class="btn btn-danger btn-delete" type="button" data-device-id="{{ $device->getKey() }}" data-device-name="{{ $device->name }}" data-bs-toggle="tooltip" data-bs-original-title="Delete Device">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
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
                const deviceId = $(this).data('device-id');
                const deviceName = $(this).data('device-name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You want to delete device "${deviceName}"? This action cannot be undone!`,
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
                            text: 'Please wait while we delete the device.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Set the form action to the delete route
                        const form = $('#delete-form');
                        form.attr('action', `{{ url('dashboard/devices') }}/${deviceId}`);

                        // Submit the form
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
