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
                            <div class="table-responsive mt-4">
                                <table class="display" id="order-datatable">
                                    <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Device</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created At</th>
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
            let table = $('#order-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('dashboard.datatable.orders') }}',
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
                    { data: 'code', name: 'orders.code' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'product_name', name: 'product_name' },
                    { data: 'device_name', name: 'device_name' },
                    { data: 'qty', name: 'orders.qty' },
                    { data: 'total_price', name: 'orders.total_price' },
                    {
                        data: 'status',
                        name: 'orders.status',
                        render: function (data) {
                            return data; // Already formatted in controller
                        }
                    },
                    { data: 'created_at', name: 'orders.created_at' },
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

        // Edit order function
        function editOrder(id) {
            window.location.href = "{{ route('dashboard.orders.edit', ':id') }}".replace(':id', id);
        }
    </script>
@endsection
