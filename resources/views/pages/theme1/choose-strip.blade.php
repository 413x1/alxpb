<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SanaPhoto</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@800&display=swap" rel="stylesheet">

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

        .font-lague-spartan {
            font-family: "League Spartan", sans-serif;
            font-optical-sizing: auto;
            font-weight: 800;
            font-style: normal;
        }

        .number-box {
            width: 100%;
            height: 300px;
            background: #0e48cf;
            background: linear-gradient(358deg, rgba(14, 72, 207, 1) 0%, rgba(43, 179, 224, 1) 100%);
            /* background-color: #3263d3;  light gray background */
            display: flex;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 10rem;             /* bigger text */
            color: #ffffff;                 /* text color */
            border: 4px solid #ffffff;      /* thicker and darker border */
            border-radius: 12px;         /* rounded corners */
            box-sizing: border-box;      /* include border in width */
        }

        .number-box-control {
            background: #0e348c;
            background: linear-gradient(2deg, rgba(14, 52, 140, 1) 0%, rgba(34, 167, 212, 1) 100%);
            color: #ffffff;                 /* text color */
            border: 4px solid #faf1f1;      /* thicker and darker border */
            border-radius: 12px;         /* rounded corners */
            box-sizing: border-box;      /* include border in width */
            cursor: pointer;
        }

        .number-box-control:hover {
            background-color: #7091f6;
        }

        .number-box-control:target {
            background-color: #5d646e;
        }

        .title-strip {
            border-radius: 12px;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 2rem;             /* bigger text */
            font-weight: bold;
            border: 4px solid #faf1f1;      /* thicker and darker border */
            background: #0e348c;
            background: linear-gradient(2deg,rgba(14, 52, 140, 1) 0%, rgba(34, 167, 212, 1) 100%);
        }

        .title-choose-strip {
            display: inline-block !important;
            border-radius: 25px;
            background: #0e348c;
            background: linear-gradient(56deg,rgba(14, 52, 140, 1) 0%, rgba(34, 167, 212, 1) 100%);
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 3rem;             /* bigger text */
            border: 4px solid #faf1f1;      /* thicker and darker border */
            color: #faf1f1;
        }

        .strip-icon {
        }

        .strip-play {
            width: 40%;
        }

        .number-box-payment {
            background: #61092D;
            background: linear-gradient(185deg,rgba(97, 9, 45, 1) 0%, rgba(207, 87, 137, 1) 100%);
            display: flex;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 4rem;             /* bigger text */
            font-weight: bold;
            color: #ffffff;                 /* text color */
            border: 4px solid #ffd4d4;      /* thicker and darker border */
            border-radius: 30px;         /* rounded corners */
            box-sizing: border-box;      /* include border in width */
        }

        .number-box-payment:hover {
            background-color: #ff549c;  /* light gray background */
            cursor: pointer;
        }

        .title-strip-payment {
            border-radius: 30px;
            background: #61092D;
            background: linear-gradient(275deg,rgba(97, 9, 45, 1) 0%, rgba(207, 87, 137, 1) 100%);
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 3rem;             /* bigger text */
            font-weight: bold;
            border: 4px solid #faf1f1;      /* thicker and darker border */
            color: #faf1f1;
        }

        .subtitle-strip-payment {
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 3rem;             /* bigger text */
            color: #faf1f1;
        }

        .payment-icon {
            max-width: 70%;
        }

        .touch-start {
            background-color: #3263d3;
            cursor: pointer;
            color: white;
            font-size: 2rem;
            border: 4px solid #faf1f1;
            font-weight: bold;
            border-radius: 30px;
        }

        .touch-start:hover {
            background-color: #7091f6;
        }

        .custom-button {
            display: inline-block;         /* Fit width to content */
            padding: 0.2rem 0 0 0;             /* Vertical padding only */
            text-align: center;
            background-color: #3263d3;     /* Example background color */
            color: white;
            font-size: 2rem;
            border: 4px solid #67a8ecff;
            border-radius: 30px;
            font-weight: 800;
            cursor: pointer;
            white-space: nowrap;           /* Prevent wrapping */
        }

        .height-80 {
            min-height: 50vh !important;
        }

        .w-80 {
            width: 70% !important;
        }

        .box-transparant {
            background-color: rgba(235, 155, 182, 0.4); /* black with 50% opacity */
            color: white;                        /* optional: for contrast */
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .quantity {
            font-size: 3rem;
        }

        .box-next {
            border-radius: 12px;
            background: #61092D;
            background: linear-gradient(63deg,rgba(97, 9, 45, 1) 0%, rgba(207, 87, 137, 1) 100%);
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 2rem;             /* bigger text */
            font-weight: bold;
            border: 5px solid #faf1f1;      /* thicker and darker border */
            color: #faf1f1;
            cursor: pointer;
        }

        .title-fiil-name {
            display: inline-block !important;
            border-radius: 25px;
            background: #0e348c;
            background: linear-gradient(56deg,rgba(14, 52, 140, 1) 0%, rgba(34, 167, 212, 1) 100%);
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 3rem;             /* bigger text */
            border: 4px solid #faf1f1;      /* thicker and darker border */
            color: #faf1f1;
        }

        .payment-text {
            color: #faf1f1;
            font-size: 3rem;
        }

        .customer-name {
            height: 2rem;           /* Make it taller */
            font-size: 2rem !important;        /* Larger text */
            padding: 1rem 1.5rem;   /* More inner space */
            border: 4px solid #61092D !important;
            background: rgba(255, 255, 255, 0.78);
        }

        .swal2-custom-bg {
            background: #f26fa6;
            background: linear-gradient(322deg,rgba(242, 111, 166, 1) 0%, rgba(153, 11, 70, 1) 100%);
            color: white; /* Optional: Ensure text is readable */
        }

    </style>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Midtrans Snap JS -->
    @if(config('app.env') == 'production')
        <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
</head>
<body style="background-image: url('{{ asset('assets/sanaphoto/background.png') }}'); background-size: cover; background-repeat: no-repeat;">
    <form method="POST">
        @csrf
        <input type="hidden" id="productId" value="{{ $product->id }}" name="productId">

        <div class="container mt-5" id="indexSection">
            <div class="row justify-content-center">
                <div class="col-4 bg-red p-4">
                    <img src="{{ asset('assets/sanaphoto/logo.png') }}" alt="" width="100%" class="img-fluid">
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-4 d-flex justify-content-center">
                    <div class="custom-button font-lague-spartan px-5" id="btnTouchStart">
                        Touch to Start
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid p-5" id="chooseStripSection">

            <div class="row mt-3 mb-3">
                <div class="col-3">
                    <div class="title-choose-strip w-100 font-lague-spartan text-center">
                        Strip Quantity
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-6 height-80">
                    <div class="box-transparant d-flex justify-content-center align-items-center" style="border-radius:25px; height:100%">
                        <img class="w-80" src=" {{ asset('assets/sanaphoto/strip.png') }}" alt="">
                    </div>
                </div>
                <div class="col-6 height-80">
                    <div class="box-transparant d-flex flex-column align-items-center justify-content-between" style="border-radius:25px; height:100%">
                        <div class="title-strip text-center font-lague-spartan quantity px-5">
                            Quantity
                        </div>
                        <div class="title-strip text-center font-lague-spartan quantity px-5" id="quantityBox">
                            Rp {{ number_format($product->price, 0, ',', '.') }}, 00
                        </div>
                        <div class="number-box qty-box font-lague-spartan" style="width:60%; height:40%;">
                                1
                        </div>
                        <div class="d-flex justify-content-center align-items-center gap-4">
                            <div class="number-box-control d-flex justify-content-center align-items-center p-5" id="qtyControlMinus">
                                <img class="strip-icon" src="{{ asset('assets/images/payments/minus.png') }}" width="50px">
                            </div>
                            <div class="number-box-control d-flex justify-content-center align-items-center p-5" id="qtyControlPlus">
                                <img class="strip-icon" src="{{ asset('assets/images/payments/plus.png') }}" width="50px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-4 m-4 align-items-center">
                <!-- Left-aligned Cancel button -->
                <div class="box-next px-5 py-3 me-auto btnBack">
                    Cancel
                </div>

                <!-- Right-aligned input and Next button -->
                <div class="col-4">
                    <input class="form-control form-control-lg font-lague-spartan customer-name" name="name" id="customerName" type="text" placeholder="John Doe" required>
                    <input type="hidden" name="qty" id="qtyInput" value="1">
                </div>

                <div class="box-next px-5 py-3" id="nextSection">
                    Next
                </div>
            </div>

        </div>


        <div class="container mt-5" id="choosePaymentSection">
            <div class="d-flex flex-column">
                <div class="d-flex flex-column justify-content-center align-items-center" style="">
                    <div class="col-6 title-strip-payment font-lague-spartan text-center mt-4">
                        Choose Payment Method
                    </div>
                    <div class="col-6 subtitle-strip-payment font-lague-spartan text-center mt-4">
                        Pilih Metode Pembayaran
                    </div>
                </div>

                <div class="d-flex flex-row justify-content-center">
                    <div class="col-4 p-4">
                        <div class="number-box-payment w-100 p-4 d-flex flex-column" id="usingPaymentGate">
                            <img class="payment-icon" src="{{ asset('assets/sanaphoto/qrcode.png') }}">
                            <div class="payment-text font-lague-spartan m-5">
                                QRIS
                            </div>
                        </div>
                    </div>
                    <div class="col-4 p-4">
                        <div class="number-box-payment w-100 p-4 d-flex flex-column" id="usingCash">
                            <img class="payment-icon" src="{{ asset('assets/sanaphoto/money.png') }}">
                            <div class="payment-text font-lague-spartan m-5">
                                Voucher
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex m-4 justify-content-center">
                <!-- Left-aligned Cancel button -->
                <div class="box-next px-5 py-3 btnBack">
                    Cancel
                </div>
            </div>
        </div>
    </form>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    function setOderId(intOrderId, qty) {
        const apiUrl = '{{ $device->api_url }}';
        const apiKey = '{{ $device->api_key }}';
        $.ajax({
            url: 'http://localhost:3020/active-order',
            type: 'GET',
            data: {
                id: intOrderId,
                qty: qty,
                api_url: apiUrl,
                api_key: apiKey,
            },
            success: function(response) {
                console.log('Order Data:', response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching active order:', textStatus, errorThrown);
            }
        });
    }

    function openDSLRBooth() {
        $.ajax({
            url: 'http://localhost:3020/open-app',
            type: 'GET',
            data: { app: 'dslrbooth' },
            success: function(response) {
                console.log('App opened successfully:', response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error opening app:', textStatus, errorThrown);
            }
        });
    }

    function shareViaEmail(email) {
        $.ajax({
            url: "{{ $device->api_url }}api/share/email",
            type: 'GET',
            data: {
                email: email,
                password: "{{$device->api_key}}"
            },
            success: function(response) {
                console.log('Email shared successfully:', response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error sharing via email:', textStatus, errorThrown);
            }
        });
    }

    function printCopies(count) {
        $.ajax({
            url: "{{ $device->api_url }}api/print",
            type: 'GET',
            data: {
                count: count
            },
            success: function(response) {
                console.log('Print request successful:', response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Print request failed:', textStatus, errorThrown);
            }
        });
    }


    // Define a function to switch sections
    function goToPaymentSection() {
        const name = $('#customerName');

        // Check if the input is empty or invalid
        if (!name.val().trim()) {
            name.focus();                // Set focus
            name[0].reportValidity();    // Trigger native browser validation message
            return; // Stop the function
        }

        // Proceed if input is valid
        $('#indexSection').hide();
        $('#chooseStripSection').hide();
        $('#choosePaymentSection').show();
    }

    function goToChooseStripSection() {
        $('#indexSection').hide();
        $('#chooseStripSection').show();
        $('#choosePaymentSection').hide();
    }

    function updateQuantity(change) {
        const MIN_QTY = 1;
        const MAX_QTY = 5;


        const $qtyBox = $('.qty-box');
        const $qtyInput = $('#qtyInput');
        const $minus = $('#qtyControlMinus');
        const $plus = $('#qtyControlPlus');

        let currentQty = parseInt($qtyBox.text().trim(), 10);
        let newQty = currentQty + change;

        // Clamp value between MIN and MAX
        newQty = Math.min(MAX_QTY, Math.max(MIN_QTY, newQty));

        // Update values
        $qtyBox.text(newQty);
        $qtyInput.val(newQty);

        // Disable buttons if limits are reached
        $minus.prop('disabled', newQty === MIN_QTY);
        $plus.prop('disabled', newQty === MAX_QTY);

        return newQty;
    }

    function showThanks() {
        Swal.fire({
            title: "Thank you!",
            html: "Please wait while we open the photo app...",
            width: 600,
            padding: "3em",
            imageUrl: "{{ asset('assets/sanaphoto/cashier.png') }}",
            timer: 5000, // 20 seconds (in milliseconds)
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                popup: 'swal2-custom-bg'
            },
            backdrop: `
                rgba(0,0,123,0.4)
                url("{{ asset('assets/images/payments/nyan-cat.gif') }}")
                left top
                no-repeat
            `,
            didOpen: () => {
                Swal.showLoading();
            },
            willClose: () => {
                console.log("Swal closed after 5 seconds");
                openDSLRBooth();
                window.location.reload();
            }
        });
    }

    function constructTransactionObject() {
        let qty = parseInt($('#qtyInput').val());
        let hargaSatuan = parseInt("{{ $product->price }}");
        let productName = "{{ $product->name }}";
        let totalHarga = hargaSatuan + (hargaSatuan/2 * (qty - 1));
        let productId = parseInt($('#productId').val());

        return {
            productId : productId,
            customerName : $('#customerName').val().trim(),
            productName : productName,
            hargaSatuan : hargaSatuan,
            qty : qty,
            totalHarga : totalHarga,
        }
    }

    function showLoading(title, message) {
        Swal.fire({
            title: title,
            text: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-custom-bg'
            },
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // payment qris or cash
    function generateConfirmationText(payments, transactionObj) {
        let confirmationHtml = `
                <div class="text-start">
                    <p><strong>Nama:</strong> ${transactionObj.customerName}</p>
                    <p><strong>Produk:</strong> ${transactionObj.productName}</p>
                    <p><strong>Quantity:</strong> ${transactionObj.qty}</p>
                    <p><strong>Harga Satuan:</strong> Rp ${new Intl.NumberFormat('id-ID').format(transactionObj.hargaSatuan)}</p>
                    <p><strong>Total:</strong> <span class="text-primary">Rp ${new Intl.NumberFormat('id-ID').format(transactionObj.totalHarga)}</span></p>
                    <p><strong>Metode Pembayaran:</strong> <span class="text-primary">${payments === 'qris' ? 'QRIS Payment' : 'Cash'}</span></p>
            `;

        if (payments === 'cash') {
            confirmationHtml += `<p><strong>Kode Voucher:</strong> <span class="text-warning">${transactionObj.voucherCode}</span></p>`;
        }

        confirmationHtml += `
                </div>
                <br>
                <p>Are you sure you want to process this order?</p>
            `;

        return confirmationHtml;
    }

    // payments qris or cash
    function generateFormData(payments, transactionObj) {
        return {
            product_id: transactionObj.productId,
            customer_name: transactionObj.customerName,
            qty: transactionObj.qty,
            payment_method: payments === 'qris' ? 'qris' : 'voucher',
            voucher_code: payments === 'cash' ? transactionObj.voucherCode : null,
            _token: $('input[name="_token"]').val()
        }
    }

    // payments qris or cash
    function showConfirmationDialog(payments, confirmationHtml, transactionObj) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Order Confirmation',
            html: confirmationHtml,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            allowEscapeKey: false,
            confirmButtonText: 'Yes, Process!',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            customClass: {
                popup: 'swal2-custom-bg'
            },
            preConfirm: () => {
                const formData = generateFormData(payments, transactionObj);

                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{ route("order.store") }}',
                        method: 'POST',
                        data: formData,
                        dataType: 'json'
                    })
                        .done(function(response) {
                            resolve(response); // ✅ Success - resolve with response
                        })
                        .fail(function(xhr) {
                            const isVoucherError = (
                                xhr.status === 400 &&
                                xhr.responseJSON &&
                                payments === 'cash' &&
                                (xhr.responseJSON.message.toLowerCase().includes('voucher') ||
                                    xhr.responseJSON.message.toLowerCase().includes('code'))
                            );

                            if (isVoucherError) {
                                const errorMessage = xhr.responseJSON.message;
                                Swal.close();

                                setTimeout(() => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Invalid Voucher',
                                        html: `
                            <div class="text-start">
                                <p><strong>Voucher Error:</strong></p>
                                <p>${errorMessage}</p>
                                <br>
                                <p>Please check your voucher code and try again.</p>
                            </div>
                        `,
                                        confirmButtonColor: '#dc3545',
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            popup: 'swal2-custom-bg'
                                        },
                                        didClose: () => {
                                            $('#voucherCode').focus().select().addClass('is-invalid');
                                            $('#voucherMessage').html(`
                                <div class="voucher-error">
                                    <i class="fas fa-exclamation-circle"></i> ${errorMessage}
                                </div>
                            `);
                                        }
                                    });
                                }, 100);

                                reject('voucher_handled'); // ✅ REJECT properly here
                                return;
                            }

                            // Other error handling
                            let errorMessage = 'An error occurred while processing the order.';

                            if (xhr.responseJSON?.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON?.errors) {
                                const allErrors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = allErrors.join('<br>');
                            }

                            Swal.showValidationMessage(errorMessage);
                            reject(new Error(errorMessage)); // ✅ Always reject on fail
                        });
                });
            },
            allowOutsideClick: () => false,
        }).then((result) => {
            if (result.isConfirmed) {
                const response = result.value;

                // Handle different payment methods based on your controller response
                if (response.success && response.payment_method === 'qris') {
                    if (response.order && response.order.snap_token) {// Debug log
                        // QRIS Payment through Midtrans
                        snap.pay(response.order.snap_token, {
                            onSuccess: function(result) {
                                updateOrderStatus(result, response.order);
                            },
                            onPending: function(result) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Payment Pending',
                                    text: 'Your payment is being processed. Please complete the payment.',
                                    confirmButtonColor: '#17a2b8',
                                    customClass: {
                                        popup: 'swal2-custom-bg'
                                    },
                                });
                            },
                            onError: function(result) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Payment Failed',
                                    text: 'Payment failed. Please try again.',
                                    confirmButtonColor: '#dc3545',
                                    customClass: {
                                        popup: 'swal2-custom-bg'
                                    },
                                });
                            },
                            onClose: function() {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Payment Cancelled',
                                    text: 'You closed the payment popup. Please try again if you want to complete the payment.',
                                    confirmButtonColor: '#ffc107',
                                    customClass: {
                                        popup: 'swal2-custom-bg'
                                    },
                                });
                            }
                        });
                    } else { // Debug log
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Error',
                            text: 'Payment gateway token not found. Please try again or contact support.',
                            confirmButtonColor: '#dc3545',
                            customClass: {
                                popup: 'swal2-custom-bg'
                            },
                        });
                    }
                } else if (response.success && response.payment_method === 'voucher') {
                    // Voucher Payment - Direct success (already paid in backend)
                    Swal.fire({
                        icon: 'success',
                        title: 'Voucher Claimed Successfully!',
                        html: `
                                <div class="text-start">
                                    <p><strong>Order ID:</strong> ${response.order.code}</p>
                                    <p><strong>Customer:</strong> ${transactionObj.customerName}</p>
                                    <p><strong>Product:</strong> {{ $product->name }}</p>
                                    <p><strong>Quantity:</strong> ${response.order.qty}</p>
                                    <p><strong>Total:</strong> Rp ${new Intl.NumberFormat('id-ID').format(response.order.total_price)}</p>
                                    <p><strong>Payment Method:</strong> <span class="text-warning">Voucher</span></p>
                                    <p><strong>Voucher Code:</strong> <span class="text-success">${transactionObj.voucherCode}</span></p>
                                    <p><strong>Status:</strong> <span class="text-success">PAID</span></p>
                                </div>
                            `,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'Continue',
                        customClass: {
                            popup: 'swal2-custom-bg'
                        },
                    }).then(() => {

                        setOderId(parseInt(response.order.id), parseInt(response.order.qty));
                        showThanks();
                        // setTimeout(openDSLRBooth, 5000);

                    });
                } else {
                    // Fallback for other cases
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Order processed successfully!',
                        confirmButtonColor: '#28a745',
                        customClass: {
                            popup: 'swal2-custom-bg'
                        },
                    }).then(() => {
                        resetForm();

                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                        }
                    });
                }
            }
        }).catch((error) => {
            // Handle the case where we manually rejected the promise for voucher errors
            if (error !== 'voucher_handled') {
                console.error('Unexpected error:', error);
            }
        });
    }

    // Function to update order status after successful payment
    function updateOrderStatus(paymentResult, orderData) {
        Swal.fire({
            title: 'Processing Payment...',
            text: 'Please wait while we update your order status.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-custom-bg'
            },
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const statusUpdateData = {
            order_id: paymentResult.order_id,
            gross_amount: paymentResult.gross_amount,
            payment_gateway_response: JSON.stringify(paymentResult),
            _token: $('input[name="_token"]').val()
        };

        $.ajax({
            url: '{{ route("order.update-status") }}',
            method: 'PUT',
            data: statusUpdateData,
            dataType: 'json'
        }).done(function(updateResponse) {
            if (updateResponse.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Success!',
                    html: `
                        <div class="text-start">
                            <p><strong>Order ID:</strong> ${paymentResult.order_id}</p>
                            <p><strong>Transaction ID:</strong> ${paymentResult.transaction_id}</p>
                            <p><sstrong>Amount:</sstrong> Rp ${new Intl.NumberFormat('id-ID').format(paymentResult.gross_amount)}</p>
                            <p><strong>Payment Method:</strong> ${paymentResult.payment_type.toUpperCase()}</p>
                            <p><strong>Status:</strong> <span class="text-success">PAID</span></p>
                        </div>
                    `,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Continue',
                    customClass: {
                        popup: 'swal2-custom-bg'
                    },
                }).then(() => {
                    setOderId(parseInt(orderData.id), parseInt(orderData.qty));
                    showThanks();
                    // setTimeout(openDSLRBooth, 5000);



                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Payment Completed',
                    text: 'Payment was successful but there was an issue updating the order status. Please contact support.',
                    confirmButtonColor: '#ffc107',
                    customClass: {
                        popup: 'swal2-custom-bg'
                    },
                });
            }
        }).fail(function(xhr) {
            let errorMessage = 'Payment was successful but failed to update order status.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage += ' Error: ' + xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'warning',
                title: 'Update Failed',
                text: errorMessage + ' Please contact support with Order ID: ' + paymentResult.order_id,
                confirmButtonColor: '#ffc107',
                customClass: {
                    popup: 'swal2-custom-bg'
                },
            });
        });
    }

    function handleCash(transactionObj) {
        Swal.fire({
            title: 'Please call the cashier',
            text: "for the code",
            color: "#f6eded",
            input: 'password',
            imageUrl: "{{ asset('assets/sanaphoto/cashier.png') }}",
            inputPlaceholder: '*****',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCancelButton: true,
            confirmButtonText: 'Apply',
            customClass: {
                popup: 'swal2-custom-bg'
            },
            inputValidator: (value) => {
                if (!value) {
                    return 'Please enter a kupon code!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                transactionObj.voucherCode = result.value;
                let confirmationHtml = generateConfirmationText('cash', transactionObj);
                showConfirmationDialog('cash', confirmationHtml, transactionObj);
            }
        });
    }

    function handlePayment() {
        $('#usingPaymentGate').on('click', function (e) {
            const transactionObj = constructTransactionObject();
            let confirmationHtml = generateConfirmationText('qris', transactionObj);
            showConfirmationDialog('qris', confirmationHtml, transactionObj)
        });

        $('#usingCash').on('click', function (e) {
            const transactionObj = constructTransactionObject();
            handleCash(transactionObj);
        })

    }

    function updatePriceDisplay(updatedPrice) {
        // Format to Indonesian currency style
        $('#quantityBox').text(
            'Rp ' + updatedPrice.toLocaleString('id-ID') + ',00'
        );
    }

    $(document).ready(function() {
        let pricePerUnit = {{ $product->price }};
        let qty = parseInt($('#qtyInput').val());
        let total = pricePerUnit * qty;

        // Initially hide the payment section
        $('#chooseStripSection').hide();
        $('#choosePaymentSection').hide();

        $('#btnTouchStart').on('click', goToChooseStripSection);

        // Attach the function to the click event
        $('#nextSection').on('click', goToPaymentSection);


        // Button click events
        $('#qtyControlPlus').on('click', function () {
            qty = updateQuantity(1);
            if(qty > 1) {
                total = pricePerUnit + (pricePerUnit/2 * (qty-1))
            } else {
                total = pricePerUnit * qty;
            }
            updatePriceDisplay(total)
        });

        $('#qtyControlMinus').on('click', function () {
            qty = updateQuantity(-1);
            if(qty > 1) {
                total = pricePerUnit - (pricePerUnit/2 * (qty-1))
            } else {
                total = pricePerUnit * qty;
            }
            updatePriceDisplay(total)
        });

        $('.btnBack').on('click', function () {
            location.reload();
        });

        // Initial state (in case default is at limit)
        updateQuantity(0);

        handlePayment();

    });


</script>


</body>
</html>
