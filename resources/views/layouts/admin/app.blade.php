<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.admin.headtag')

    @yield('after-css')
</head>
<body>
<!-- tap on top starts-->
<div class="tap-top"><i class="iconly-Arrow-Up icli"></i></div>
<!-- tap on tap ends-->
<!-- loader-->
<div class="loader-wrapper">
    <div class="loader"><span></span><span></span><span></span><span></span><span></span></div>
</div>
<!-- page-wrapper Start-->
<div class="page-wrapper compact-wrapper" id="pageWrapper">

    @include('layouts.admin.header')

    <!-- Page Body Start-->
    <div class="page-body-wrapper">

        @include('layouts.admin.sidebar')

        <div class="page-body">
            <div class="container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <h2>{{ $pageTitle }}</h2>
                        </div>
                        <div class="col-sm-6 col-12">
                            <ol class="breadcrumb">
                                @foreach($breadcrumbs as $index => $crumb)
                                    <li class="breadcrumb-item {{ $crumb['active'] ? 'active' : '' }}">
                                        @if($index === 0)
                                            {{-- Special treatment for Home icon --}}
                                            <a href="{{ $crumb['url'] }}"><i class="iconly-Home icli svg-color"></i></a>
                                        @elseif(!$crumb['active'])
                                            <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                                        @else
                                            {{ $crumb['name'] }}
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')

        </div>

        @include('layouts.admin.footer')
    </div>
</div>

    @include('layouts.admin.scripts')
    @yield('after-js')

</body>
</html>
