@extends('layouts.app')

@section('css')
    <style>
        .boxfrom h3 {
            text-align: center;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="row justify-content-center align-items-center g-5 py-5">
        <div class="col-4 boxfrom d-flex flex-column justify-content-center">
            <h3 class="mb-4">Sign Device</h3>
            <form action="{{ route('device.auth') }}" method="post">
                @csrf
                <div class="mb-4">
                    <input type="text" name="code" class="form-control form-control-lg" id="" placeholder="Masukan code" required>
                </div>
                <div class="mb-4 d-flex flex-column justify-content-center">
                    <button type="submit" class="btn btn-success">Masuk</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')

@endsection
