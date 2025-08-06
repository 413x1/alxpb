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

        .number-box {
            width: 100%;
            height: 300px;
            background-color: #6397e8;  /* light gray background */
            display: flex;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 4rem;             /* bigger text */
            font-weight: bold;
            color: #ffffff;                 /* text color */
            border: 2px solid #888;      /* thicker and darker border */
            border-radius: 12px;         /* rounded corners */
            box-sizing: border-box;      /* include border in width */
        }

        .number-box-control {
            width: 50%;
            height: 75px;
            background-color: #6397e8;  /* light gray background */
            display: flex;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 2rem;             /* bigger text */
            font-weight: bold;
            color: #ffffff;                 /* text color */
            border: 2px solid #faf1f1;      /* thicker and darker border */
            border-radius: 12px;         /* rounded corners */
            box-sizing: border-box;      /* include border in width */
            cursor: pointer;
        }

        .number-box-control:hover {
            background-color: #8cb5f4;
        }

        .number-box-control:target {
            background-color: #5d646e;
        }

        .title-strip {
            border-radius: 12px;
            background-color: #6397e8;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 2rem;             /* bigger text */
            font-weight: bold;
            border: 2px solid #faf1f1;      /* thicker and darker border */
        }

        .triangle-right {
            width: 0;
            height: 0;
            color: #e1e4ec;
            border-top: 30px solid transparent;
            border-bottom: 30px solid transparent;
            border-left: 40px solid #f3e8e8; /* triangle color */
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
                    Thank You
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-4 title-strip text-center">
                    Please take your photo at the front desk
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
