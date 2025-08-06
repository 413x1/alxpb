<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SanaPhoto</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .hero {
            height: 100vh;
            background-image: url('{{ asset('assets/sanaphoto/background.png') }}'); /* Replace with your actual image path */
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        }

        input[type="text"] {
            border: 2px solid pink;
            border-radius: 10px;
            padding: 10px;
            color: black;
            background: rgba(255, 255, 255, 0.5);
            outline: none;
            font-size: 16px;
            margin-top: 20px;
        }

        input::placeholder {
            color: pink;
            opacity: 1;
        }

        button {
            background-color: blue;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: darkblue;
        }
    </style>

</head>
<body>
    <div class="hero">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-3 col-lg-3 bg-red p-4">
                    <img src="{{ asset('assets/sanaphoto/logo.png') }}" alt="" width="100%" class="img-fluid">
                    <form action="{{ route('device.auth') }}" method="post">
                        @csrf
                        <input class="form-control form-control-lg mt-5" name="code" type="text" placeholder="device code" required>
                        <button type="submit" class="btn btn-primary btn-lg mt-3 w-100">ENTER</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
