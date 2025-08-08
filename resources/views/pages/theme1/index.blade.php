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
            font-family: Arial, sans-serif;
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

        .custom-input {
            height: 60px;           /* Make it taller */
            font-size: 2rem !important;        /* Larger text */
            padding: 1rem 1.5rem;   /* More inner space */
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

        .btn-submit {
            background: #0e348c;
            border: 4px solid #ffffff;
            font-size: 2rem;
            border-radius: 30px;
            background: linear-gradient(56deg,rgba(14, 52, 140, 1) 0%, rgba(34, 167, 212, 1) 100%);
        }

        .font-lague-spartan {
            font-family: "League Spartan", sans-serif;
            font-optical-sizing: auto;
            font-weight: 800;
            font-style: normal;
        }
    </style>

</head>
<body style="background-image: url('{{ asset('assets/sanaphoto/background.png') }}'); background-size: cover; background-repeat: no-repeat;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-3 col-xxl-4 bg-red p-4 mt-5">
                <img src="{{ asset('assets/sanaphoto/logo.png') }}" alt="" width="100%" class="img-fluid">
                <form action="{{ route('device.auth') }}" method="post">
                    @csrf
                    <input class="form-control form-control-lg mt-5 custom-input" name="code" type="text" placeholder="device code" required>
                    <button type="submit" class="btn btn-primary font-lague-spartan btn-lg mt-3 w-100 btn-submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
