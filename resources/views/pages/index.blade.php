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
        <div class="d-flex flex-nowrap overflow-auto py-3">
        <!-- Image items -->
            @if ($banners)
                @foreach ($banners as $banner)
                    <div class="col-4 flex-shrink-0 px-2">
                        <div class="ratio ratio-4x6">
                            <img src="{{ $banner->image_url }}" class="img-thumbnail w-100" alt="{{ $banner->name }}">
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="d-flex justify-content-center mt-3">
                <div class="col-4 d-flex flex-column">
                    <div class="d-grid gap-3 col-6 mx-auto">
                        <a href="{{ route('order.index') }}" class="btn btn-success btn-lg" type="button">Mulai Proses</a>
                        <a href="#" class="btn btn-warning btn-lg" type="button">Lihat Tutorial</a>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('js')

@endsection
