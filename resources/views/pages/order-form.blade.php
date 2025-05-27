@extends('layouts.app')

@section('css')
    <style>
        .ratio-4x6 {
            --bs-aspect-ratio: calc(6 / 4 * 100%);
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-6 p-2">
                <div id="carouselExample" class="col-10 carousel slide">
                    <div class="carousel-inner">
                        @if ($product->banners)
                            @foreach ($product->banners as $banner)
                                <div class="carousel-item @if($loop->first) active @endif">
                                    <img src="{{ $banner->url }}" class="img-thumbnail d-block w-100" alt="{{ $banner->name }}">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-4 p-2 ml-3">
                <form action="" method="post">
                    <h3>Masukan data diri</h3>
                    <div class="mb-3">
                        <label for="productName" class="form-label">Tipe :</label>
                        <input type="text" class="form-control form-control-lg" id="productName" value="{{ $product->name }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Harga :</label>
                        <input type="text" class="form-control form-control-lg" id="productPrice" value="{{ $product->price }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Nama :</label>
                        <input type="text" class="form-control form-control-lg" id="customerName" placeholder="masukan nama">
                    </div>
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Qty :</label>
                        <input type="number" class="form-control form-control-lg" min="1" value="1" id="customerName" placeholder="masukan jumlah">
                    </div>
                    <div class="mb-3">
                        <div class="d-grid gap-3 col-6 mx-auto">
                            <a href="#" class="btn btn-success btn-lg" type="button">Proses</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    
@endsection