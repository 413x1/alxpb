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
                            <a href="{{ route('dashboard.banners.create') }}" class="btn btn-info text-light"  data-bs-toggle="tooltip" data-bs-original-title="btn btn-info btn-add-banner">Add New Banner</a>
                            <div class="table-responsive signal-table">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">View</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Path</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($banners as $banner)
                                        <tr>
                                            <th scope="row">{{ $banners->firstItem() + $loop->index }}</th>
                                            <td>
                                                <img class="img-30 me-2" src="{{ $banner->image_url }}" alt="">
                                            </td>
                                            <td>{{ $banner->name }}</td>
                                            <td>{{ $banner->type }}</td>
                                            <td>{{ $banner->url }}</td>
                                            <td>{{ $banner->is_active ? 'Active' : 'Non active' }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.banners.edit', $banner->getKey()) }}" class="btn btn-secondary me-2" data-bs-toggle="tooltip" data-bs-original-title="Edit Banner">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <button class="btn btn-danger btn-delete" type="button" data-banner-id="{{ $banner->getKey() }}" data-banner-name="{{ $banner->name }}" data-bs-toggle="tooltip" data-bs-original-title="Delete Banner">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $banners->links() }}
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
                const bannerId = $(this).data('banner-id');
                const bannerName = $(this).data('banner-name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You want to delete banner "${bannerName}"? This action cannot be undone!`,
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
                            text: 'Please wait while we delete the banner.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Set the form action to the delete route
                        const form = $('#delete-form');
                        form.attr('action', `{{ url('dashboard/banners') }}/${bannerId}`);

                        // Submit the form
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
