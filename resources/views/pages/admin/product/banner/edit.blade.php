@extends('layouts.admin.app')

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dashboard.product.banners.update', [
    'product' => $product,
    'banner' => $banner,
]) }}" method="POST" enctype="multipart/form-data" class="form theme-form basic-form">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Banner Title <span class="text-danger">*</span></h5>
                                        <input class="form-control @error('title') is-invalid @enderror"
                                               type="text"
                                               name="title"
                                               value="{{ old('title', $banner->title) }}"
                                               placeholder="Enter banner title *">
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Description</h5>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  name="description"
                                                  rows="4"
                                                  placeholder="Enter banner description">{{ old('description', $banner->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                                {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <h5 class="f-w-600 mb-2">Current Banner Image</h5>
                                        @if($banner->url)
                                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="img-thumbnail mb-3" style="max-height: 200px;">
                                        @endif

                                        <h5 class="f-w-600 mb-2">Upload new banner image</h5>
                                        <input type="file"
                                               name="image"
                                               id="bannerImage"
                                               class="form-control @error('image') is-invalid @enderror"
                                               accept="image/*">
                                        <small class="form-text text-muted">
                                            Leave empty to keep current image. Accepted formats: JPG, PNG, GIF. Max size: 2MB
                                        </small>
                                        @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div id="imagePreview" class="mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success me-3">Update</button>
                                        <a href="{{ route('dashboard.products.edit') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid ends-->
@endsection

@section('after-js')
    <script>
        $(document).ready(function() {
            // Image preview
            $('#bannerImage').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-height: 200px;">');
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
