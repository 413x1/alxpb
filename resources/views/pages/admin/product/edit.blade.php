@extends('layouts.admin.app')

@section('after-css')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dashboard.products.update', $product) }}" method="POST" class="form theme-form basic-form">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Product Name <span class="text-danger">*</span></h5>
                                        <input class="form-control @error('name') is-invalid @enderror"
                                               type="text"
                                               name="name"
                                               value="{{ old('name', $product->name) }}"
                                               placeholder="Enter product name *"
                                               required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Description</h5>
                                        <div id="description-editor" style="height: 300px;"></div>
                                        <input type="hidden" name="description" id="description-input" value="{{ old('description', $product->description) }}">
                                        @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Price <span class="text-danger">*</span></h5>
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text">Rp</span>
                                            <input class="form-control @error('price') is-invalid @enderror"
                                                   type="number"
                                                   name="price"
                                                   value="{{ old('price', $product->price) }}"
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   required>
                                            @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Status</h5>
                                        <div class="form-check checkbox checkbox-primary">
                                            <input class="form-check-input"
                                                   id="is_active"
                                                   type="checkbox"
                                                   name="is_active"
                                                   value="1"
                                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success me-3">Update Product</button>
                                        <a href="{{ route('dashboard.products.index') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header card-no-border pb-0">
                            <a href="{{ route('dashboard.product.banners.create', $product) }}" class="btn btn-info text-light" data-bs-toggle="tooltip" data-bs-original-title="btn btn-info btn-add-device">Add New Banner</a>
                            <div class="table-responsive signal-table">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($banners as $banner)
                                        <tr>
                                            <th scope="row">{{ $banners->firstItem() + $loop->index }}</th>
                                            <td>{{ $banner->title }}</td>
                                            <td>{{ $banner->description ?? '-' }}</td>
                                            <td>
                                                <img class="img-30 me-2" src="{{ $banner->image_url }}" alt="">
                                            </td>
                                            <td>{{ $banner->is_active ? 'Active' : 'Non active' }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.product.banners.edit', [
    'product' => $product->getKey(),
    'banner' => $banner->getKey()
]) }}" class="btn btn-secondary" type="button" data-bs-toggle="tooltip" data-bs-original-title="btn btn-secondary">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-original-title="btn btn-danger" onclick="deleteBanner({{ $product->getKey() }}, {{ $banner->getKey() }})" title="Delete">
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
    <!-- Container-fluid ends-->
@endsection

@section('after-js')
    <!-- Quill Editor CSS and JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="{{ asset('assets/theme/js/sweetalert/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill editor
            var quill = new Quill('#description-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                },
                placeholder: 'Enter product description (optional)...'
            });

            // Set initial content
            var initialContent = document.getElementById('description-input').value;
            if (initialContent) {
                quill.root.innerHTML = initialContent;
            }

            // Update hidden input when content changes
            quill.on('text-change', function() {
                document.getElementById('description-input').value = quill.root.innerHTML;
            });

            // Update hidden input before form submission
            document.querySelector('form').addEventListener('submit', function() {
                document.getElementById('description-input').value = quill.root.innerHTML;
            });
        });

        function deleteBanner(productId, id) {
            console.log(productId, id);
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
                        url: "{{ route('dashboard.product.banners.destroy', ['product' => '__PRODUCT_ID__', 'banner' => '__BANNER_ID__']) }}".replace('__PRODUCT_ID__', productId).replace('__BANNER_ID__', id).replace(':id', id),
                        type: 'POST',
                        data: {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete banner',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
