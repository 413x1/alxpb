<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @yield('css')

</head>
<body data-bs-theme="dark">

    <div class="container col-xxl-8 px-4 py-5">
        @yield('content')
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

    @yield('js')
</body>
</html>
