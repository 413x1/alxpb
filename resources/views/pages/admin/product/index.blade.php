@extends('layouts.admin.app')

@section('after-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/theme/css/vendors/owlcarousel.css') }}"/>
@endsection

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header card-no-border pb-0 d-flex justify-content-between">
                        <h3>{{ $product->name }}</h3>
                        <a href="{{ route('dashboard.products.edit') }}" class="btn btn-primary">Edit</a>
                    </div>
                    <div class="card-body">
                        <h4>Description :</h4>
                        {!! $product->description !!}

                        <h4>Price :</h4>
                        {{ GeneralHelper::decimalToRupiah($product->price) }}

                        <h4>Status :</h4>
                        {{ $product->status ? 'Active' : 'Non active' }}
                        <div class="w-25 mt-4">
                            <h4>Banners :</h4>
                            <div class="product-slider owl-carousel owl-theme mb-2" id="sync1">
                                @foreach($product->banners as $banner)
                                    <div class="item"><img src="{{ $banner->image_url }}" alt=""/></div>
                                @endforeach
                            </div>
                            <div class="owl-carousel owl-theme" id="sync2">
                                @foreach($product->banners as $banner)
                                    <div class="item"><img src="{{ $banner->image_url }}" alt=""/></div>
                                @endforeach
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
    <!-- owlcarousel-->
    <script src="{{ asset('assets/theme/js/owlcarousel/owl.carousel.js') }}"></script>
    <!-- page_owlcarousel-->
    <script src="{{ asset('assets/theme/js/owlcarousel/owl-custom.js') }}"> </script>
    <!-- ecommerce-->
    <script src="{{ asset('assets/theme/js/ecommerce.js') }}"></script>
@endsection
