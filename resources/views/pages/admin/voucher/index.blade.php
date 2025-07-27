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
        #voucher-datatable th,
        #voucher-datatable td {
            white-space: nowrap;
            padding: 8px 12px;
        }

        /* Specific width for certain columns */
        #voucher-datatable th:nth-child(1) { width: 50px; }   /* No. */
        #voucher-datatable th:nth-child(2) { width: 80px; }   /* Code */
        #voucher-datatable th:nth-child(3) { width: 200px; }  /* Description */
        #voucher-datatable th:nth-child(4) { width: 100px; }  /* Is Willcard */
        #voucher-datatable th:nth-child(5) { width: 100px; }  /* Used Status */
        #voucher-datatable th:nth-child(6) { width: 130px; }  /* Used At */
        #voucher-datatable th:nth-child(7) { width: 100px; }  /* Created By */
        #voucher-datatable th:nth-child(8) { width: 100px; }  /* Action */

        /* Modal styling improvements */
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .form-check-label {
            font-weight: 500;
        }

        .btn-generate:hover {
            background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        /* Button group styling */
        .button-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-info-gradient {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border: none;
            font-weight: 600;
        }
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
                            <div class="button-group">
                                <a href="{{ route('dashboard.vouchers.create') }}" class="btn btn-info-gradient text-light">
                                    Create Voucher
                                </a>
                                <button type="button" class="btn btn-success text-light"
                                        data-bs-toggle="modal" data-bs-target="#generateVoucherModal">
                                    Generate Vouchers
                                </button>
                            </div>
                            <div class="table-responsive mt-4">
                                <table class="display table table-striped table-hover" id="voucher-datatable" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Is Willcard</th>
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

    <!-- Generate Voucher Modal -->
    <div class="modal fade" id="generateVoucherModal" tabindex="-1" aria-labelledby="generateVoucherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateVoucherModalLabel">
                        Generate Vouchers
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="generateVoucherForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="voucherCount" class="form-label fw-bold">
                                Number of Vouchers
                            </label>
                            <input type="number" class="form-control form-control-lg" id="voucherCount"
                                   name="count" min="1" max="100" value="1" required>
                            <div class="form-text">
                                You can generate between 1 and 100 vouchers at once.
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isWillcard" name="is_willcard" value="1">
                            <label class="form-check-label fw-bold" for="isWillcard">
                                Is Wildcard Voucher
                            </label>
                            <div class="form-text mt-2">
                                Wildcard vouchers can be used for any service or product.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success text-white">
                            <span class="spinner-border spinner-border-sm me-2" id="generateSpinner" style="display: none;"></span>
                            <span id="generateBtnText">Generate Vouchers</span>
                        </button>
                    </div>
                </form>
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
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                responsive: false,
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
                        searchable: false,
                        width: '50px'
                    },
                    {
                        data: 'code',
                        name: 'vouchers.code',
                        width: '80px'
                    },
                    {
                        data: 'description',
                        name: 'vouchers.description',
                        width: '200px',
                        render: function (data) {
                            return data ? data : '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'is_willcard',
                        name: 'vouchers.is_willcard',
                        render: function (data) {
                            return data
                                ? '<span class="badge badge-success">Yes</span>'
                                : '<span class="badge badge-danger">No</span>';
                        },
                        width: '100px',
                        className: 'text-center'
                    },
                    {
                        data: 'is_used',
                        name: 'vouchers.is_used',
                        render: function (data) {
                            return data
                                ? '<span class="badge badge-danger">Used</span>'
                                : '<span class="badge badge-success">Available</span>';
                        },
                        width: '100px',
                        className: 'text-center'
                    },
                    {
                        data: 'used_at',
                        name: 'vouchers.used_at',
                        width: '130px'
                    },
                    {
                        data: 'created_by_name',
                        name: 'users.name',
                        width: '100px'
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
                order: [[0, 'desc']], // Order by number column
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

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Generate Voucher Form Submission
            $('#generateVoucherForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const count = $('#voucherCount').val();

                // Show loading state
                showLoadingState(true);

                $.ajax({
                    url: '{{ route('dashboard.vouchers.generate-voucher-code') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showLoadingState(false);

                        if (response.success) {
                            // Close modal
                            $('#generateVoucherModal').modal('hide');

                            // Reset form
                            $('#generateVoucherForm')[0].reset();
                            $('#voucherCount').val(1);

                            // Refresh DataTable
                            table.ajax.reload();

                            // Show success message
                            Swal.fire({
                                title: 'Success!',
                                html: `
                                    <div class="text-center">
                                        <p class="mb-2">${response.message}</p>
                                        <p class="text-muted">Generated <strong>${count}</strong> voucher${count > 1 ? 's' : ''} successfully!</p>
                                    </div>
                                `,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'animated bounceIn'
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Failed to generate vouchers',
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function(xhr) {
                        showLoadingState(false);

                        let errorMessage = 'Failed to generate vouchers';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }

                        Swal.fire({
                            title: 'Error!',
                            html: errorMessage,
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            });

            // Function to show/hide loading state
            function showLoadingState(loading) {
                if (loading) {
                    $('#generateSpinner').show();
                    $('#generateBtnText').text('Generating...');
                    $('#generateVoucherForm button[type="submit"]').prop('disabled', true);
                } else {
                    $('#generateSpinner').hide();
                    $('#generateBtnText').text('Generate Vouchers');
                    $('#generateVoucherForm button[type="submit"]').prop('disabled', false);
                }
            }

            // Reset form when modal is closed
            $('#generateVoucherModal').on('hidden.bs.modal', function () {
                $('#generateVoucherForm')[0].reset();
                $('#voucherCount').val(1);
                showLoadingState(false);
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
                        text: 'Please wait while we delete the voucher.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

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
                                    timer: 2000,
                                    showConfirmButton: false
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
