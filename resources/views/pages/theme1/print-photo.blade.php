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

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>
<body>
    <div class="hero">
        <div class="container mt-5">
            <div class="row d-flex justify-content-center align-items-center" style="height: 100px;">
                <div class=" col-4 title-strip text-center">
                    Print & Share Photo
                </div>
            </div>

            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-4 text-center">
                    Type your email
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-4">
                    <form method="POST">
                        @csrf
                        <input class="form-control form-control-lg" type="text" id="emailDest" placeholder="example@email.com">
                        <button type="button" class="btn btn-primary btn-lg btn-share">Share</button>
                    </form>
                    <div id="countdown"></div>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    function printCopies() {
        const ncopy = parseInt("{{ $order->qty }}") - 1
        $.ajax({
            url: "{{ $device->api_url }}api/print",
            type: 'GET',
            data: {
                count: ncopy,
                password: "{{$device->api_key}}"
            },
            success: function(response) {
                console.log('Print request successful:', response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Print request failed:', textStatus, errorThrown);
            }
        });
    }

    function shareViaEmail() {

        const emailForm = $('#emailDest');
        if(!emailForm.val().trim()) {
            emailForm.focus();
            emailForm[0].reportValidity();
            return;
        }

        $.ajax({
            url: "{{ $device->api_url }}api/share/email",
            type: 'GET',
            data: {
                email: emailForm.val().trim(),
                password: "{{$device->api_key}}"
            },
            success: function(response) {
                showSwalWithTimer('Success', 'Email shared successfully!', 'success', 5, ()=> {
                    window.close()
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showSwalWithTimer('Failed', 'Failed to share email.', 'error', 5, ()=> {
                    window.close()
                });
            }
        });
    }

    function showSwalWithTimer(title, text, icon, seconds, callback) {
        let timerInterval;
        Swal.fire({
            title: title,
            html: `${text}<br><strong>Closing in <b id="countdown">${seconds}</b> second(s)...</strong>`,
            icon: icon,
            timer: seconds * 1000,
            timerProgressBar: true,
            didOpen: () => {
                const countdown = Swal.getHtmlContainer().querySelector('#countdown');
                timerInterval = setInterval(() => {
                    countdown.textContent = parseInt(countdown.textContent) - 1;
                }, 1000);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then(() => {
            if (typeof callback === "function") {
                callback();
            }
        });
    }

    function startCountdownAndClose(seconds = 30) {
        let remaining = seconds;

        const interval = setInterval(() => {
            $('#countdown').text(`This tab will close in ${remaining} second(s)...`);

            if (remaining <= 0) {
                clearInterval(interval);
                window.close(); // Will only work if the tab was opened via window.open()
            }

            remaining--;
        }, 1000);
    }

    $(document).ready(function() {

        startCountdownAndClose(120);
        printCopies()

        $('.btn-share').on('click', shareViaEmail);

    });

</script>
</body>
</html>
