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
                            <button class="btn btn-info text-light" type="button" data-bs-toggle="tooltip" data-bs-original-title="btn btn-info btn-add-device">Generate Vouchers</button>
                            <div class="table-responsive">
                                <table class="display" id="voucher-datatable">
                                    <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Used Status</th>
                                        <th scope="col">Used At</th>
                                        <th scope="col">By</th>
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

    <script>
        $(document).ready(function() {
            $("#basic-1").DataTable();

            $('#voucher-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('dashboard.voucher.datatable') }}', // Replace with your correct route
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
                            return data ? 'Used' : 'Unused';
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
                ]
            });
        });
    </script>
@endsection
