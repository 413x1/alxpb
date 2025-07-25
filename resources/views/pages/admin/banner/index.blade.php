@extends('layouts.admin.app')


@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header card-no-border pb-0">
                            <a href="{{ route('dashboard.banners.create') }}" class="btn btn-info text-light"  data-bs-toggle="tooltip" data-bs-original-title="btn btn-info btn-add-device">Add New Banner</a>
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
                                                <a href="{{ route('dashboard.banners.edit', $banner->getKey()) }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-original-title="btn btn-secondary">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
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
@endsection
