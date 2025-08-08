<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SanaPhoto</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <style>

    </style>

</head>
<body style="background-image: url('{{ asset('assets/sanaphoto/background.png') }}'); background-size: cover; background-repeat: no-repeat;">
    <div class="container text-center">
        <div class="row justify-content-md-center">
            <div class="col-md-auto">
            Variable width content
            </div>
        </div>
        <div class="row">
            <div class="col">
            1 of 3
            </div>
            <div class="col-md-auto">
            Variable width content
            </div>
            <div class="col col-lg-2">
            3 of 3
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
</body>\
</html>
