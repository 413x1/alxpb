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

        .number-box-payment {
            width: 100%;
            height: 300px;
            background-color: #cc447d;  /* light gray background */
            display: flex;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 4rem;             /* bigger text */
            font-weight: bold;
            color: #ffffff;                 /* text color */
            border: 4px solid #ffd4d4;      /* thicker and darker border */
            border-radius: 12px;         /* rounded corners */
            box-sizing: border-box;      /* include border in width */
        }

        .number-box-payment:hover {
            background-color: #ff549c;  /* light gray background */
            cursor: pointer;
        }

        .title-strip-payment {
            border-radius: 12px;
            background-color: #cc447d;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 2rem;             /* bigger text */
            font-weight: bold;
            border: 4px solid #faf1f1;      /* thicker and darker border */
        }

        .payment-icon {
            max-width: 70%;
        }

    </style>

</head>
<body>
    <div class="hero">
        <div class="container mt-5">
            <div class="row d-flex justify-content-center align-items-center" style="height: 100px;">
                <div class=" col-4 title-strip text-center">
                    Choose Payment
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-4 p-4">
                    <div class="number-box">
                        <img class="payment-icon" src="{{ asset('assets/images/payments/qr.png') }}">
                    </div>
                </div>
                <div class="col-4 p-4">
                    <div class="number-box">
                        <img class="payment-icon" src="{{ asset('assets/images/payments/money.png') }}">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
