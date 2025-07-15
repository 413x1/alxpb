@extends('layouts.admin.app')

@section('datatable-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/theme/css/vendors/datatables.css') }}">
    <!-- iconly-icon-->
    <link rel="stylesheet" href="{{ asset('assets/theme/css/themify.css') }}">
@endsection

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header card-no-border pb-0">
                            <a href="{{ route('dashboard.vouchers.create') }}" class="btn btn-info text-light"  data-bs-toggle="tooltip" data-bs-original-title="btn btn-info btn-add-device">Generate Vouchers</a>
                            <div class="table-responsive mt-4">
                                <table class="display" id="voucher-datatable">
                                    <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Used Status</th>
                                        <th scope="col">Used At</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('after-js')
    <!-- datatable-->
    <script src="{{ asset('assets/theme/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/theme/js/sweetalert/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            let table = $('#voucher-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('dashboard.datatable.vouchers') }}',
                    type: 'GET'
                },
                columns: [
                    {
                        data: null,
                        name: 'rownum',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'code', name: 'vouchers.code' },
                    { data: 'description', name: 'vouchers.description' },
                    {
                        data: 'is_used',
                        name: 'vouchers.is_used',
                        render: function (data) {
                            return data
                                ? '<span class="badge badge-danger">Used</span>'
                                : '<span class="badge badge-success">Available</span>';
                        }
                    },
                    { data: 'used_at', name: 'vouchers.used_at' },
                    { data: 'created_by_name', name: 'users.name' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']]
            });
        });

        // Edit voucher function
        function editVoucher(id) {
            window.location.href = "{{ route('dashboard.vouchers.edit', ':id') }}".replace(':id', id);
        }

        // Delete voucher function
        function deleteVoucher(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('dashboard.vouchers.destroy', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#voucher-datatable').DataTable().ajax.reload();

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete voucher',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
