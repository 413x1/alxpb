@extends('layouts.admin.app')


@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row gap-2">
            <div class="col-sm-3">
                <div class="card small-widget mb-sm-0">
                    <div class="card-body primary"><span class="f-light">Banner</span>
                        <div class="d-flex align-items-end gap-1">
                            <h4>{{$banner}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card small-widget mb-sm-0">
                    <div class="card-body primary"><span class="f-light">Device</span>
                        <div class="d-flex align-items-end gap-1">
                            <h4>{{$device}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card small-widget mb-sm-0">
                    <div class="card-body primary"><span class="f-light">User</span>
                        <div class="d-flex align-items-end gap-1">
                            <h4>{{$user}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card small-widget mb-sm-0">
                    <div class="card-body primary"><span class="f-light">Order</span>
                        <div class="d-flex align-items-end gap-1">
                            <h4>{{$order}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card small-widget mb-sm-0">
                    <div class="card-body primary"><span class="f-light">Voucher</span>
                        <div class="d-flex align-items-end gap-1">
                            <h4>{{$voucher}}</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection
