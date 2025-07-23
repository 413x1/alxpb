@extends('layouts.admin.app')

@section('datatable-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/theme/css/vendors/datatables.css') }}">
    <!-- iconly-icon-->
    <link rel="stylesheet" href="{{ asset('assets/theme/css/themify.css') }}">
    <style>
        /* Custom styles for better table appearance */
        .dataTables_wrapper .dataTables_scroll {
            clear: both;
        }

        .dataTables_scrollBody {
            border: 1px solid #dee2e6;
        }

        /* Ensure action buttons don't wrap */
        .action-buttons {
            white-space: nowrap;
            min-width: 100px;
        }

        /* Fix for column widths */
        #order-datatable th,
        #order-datatable td {
            white-space: nowrap;
            padding: 8px 12px;
        }

        /* Specific width for certain columns */
        #order-datatable th:nth-child(1) { width: 50px; }   /* No. */
        #order-datatable th:nth-child(2) { width: 180px; }  /* Code */
        #order-datatable th:nth-child(3) { width: 120px; }  /* Customer */
        #order-datatable th:nth-child(4) { width: 120px; }  /* Product */
        #order-datatable th:nth-child(5) { width: 100px; }  /* Device */
        #order-datatable th:nth-child(6) { width: 60px; }   /* Qty */
        #order-datatable th:nth-child(7) { width: 120px; }  /* Total Price */
        #order-datatable th:nth-child(8) { width: 100px; }  /* Status */
        #order-datatable th:nth-child(9) { width: 130px; }  /* Created At */
        #order-datatable th:nth-child(10) { width: 100px; } /* Action */
    </style>
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
                                <table class="display table table-striped table-hover" id="order-datatable" style="width:100%">
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
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                responsive: false,
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
                        searchable: false,
                        width: '50px'
                    },
                    {
                        data: 'code',
                        name: 'orders.code',
                        width: '180px'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        width: '120px'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name',
                        width: '120px'
                    },
                    {
                        data: 'device_name',
                        name: 'device_name',
                        width: '100px'
                    },
                    {
                        data: 'qty',
                        name: 'orders.qty',
                        width: '60px',
                        className: 'text-center'
                    },
                    {
                        data: 'total_price',
                        name: 'orders.total_price',
                        width: '120px',
                        className: 'text-end'
                    },
                    {
                        data: 'status',
                        name: 'orders.status',
                        render: function (data) {
                            return data; // Already formatted in controller
                        },
                        width: '100px',
                        className: 'text-center'
                    },
                    {
                        data: 'created_at',
                        name: 'orders.created_at',
                        width: '130px'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '100px',
                        className: 'text-center action-buttons'
                    }
                ],
                order: [[8, 'desc']], // Order by created_at column
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });

        // Edit order function
        function editOrder(id) {
            window.location.href = "{{ route('dashboard.orders.edit', ':id') }}".replace(':id', id);
        }

        // Delete order function
        function deleteOrder(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete the order.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('dashboard.orders.destroy', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#order-datatable').DataTable().ajax.reload();

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete order',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
