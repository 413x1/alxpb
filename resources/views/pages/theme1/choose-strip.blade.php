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
            background-color: #3263d3;  /* light gray background */
            display: flex;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 4rem;             /* bigger text */
            font-weight: bold;
            color: #ffffff;                 /* text color */
            border: 4px solid #ffffff;      /* thicker and darker border */
            border-radius: 12px;         /* rounded corners */
            box-sizing: border-box;      /* include border in width */
        }

        .number-box-control {
            width: 50%;
            height: 75px;
            background-color: #3263d3;  /* light gray background */
            display: flex;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 2rem;             /* bigger text */
            font-weight: bold;
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
            background-color: #3263d3;
            justify-content: center;     /* center horizontally */
            align-items: center;         /* center vertically */
            font-size: 2rem;             /* bigger text */
            font-weight: bold;
            border: 4px solid #faf1f1;      /* thicker and darker border */
        }

        .strip-icon {
            width: 30%;
        }

        .strip-play {
            width: 40%;
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

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Midtrans Snap JS -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <div class="hero">
        <form method="POST">
            @csrf
            <input type="hidden" id="productId" value="{{ $product->id }}" name="productId">

            <div class="container mt-5" id="chooseStripSection">

                <div class="row d-flex justify-content-center align-items-center" style="height: 100px;">
                    <div class=" col-4 title-strip text-center">
                        NUMBER OF STRIP
                    </div>
                </div>

                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-4">
                        <h5>Your name : </h5>
                        <input class="form-control form-control-lg" name="name" id="customerName" type="text" placeholder="John Doe" required>
                        <input type="hidden" name="qty" id="qtyInput" value="1">
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-2 p-4 d-flex justify-content-end align-items-center">
                        <div class="number-box-control" id="qtyControlMinus">
                            <img class="strip-icon" src="{{ asset('assets/images/payments/minus.png') }}">
                        </div>
                    </div>
                    <div class="col-4 p-4">
                        <div class="number-box qty-box">
                            1
                        </div>
                    </div>
                    <div class="col-2 p-4 d-flex justify-content-start align-items-center">
                        <div class="number-box-control" id="qtyControlPlus">
                            <img class="strip-icon" src="{{ asset('assets/images/payments/plus.png') }}">
                        </div>
                    </div>
                </div>

                <div class="row d-flex justify-content-end align-items-center" style="height: 100px;">
                    <div class="col-2 p-4 d-flex justify-content-end align-items-center m-2">
                        <div class="number-box-control" id="nextSection">
                            <img class="strip-play" src="{{ asset('assets/images/payments/play.png') }}">
                        </div>
                    </div>
                </div>
            </div>


            <div class="container mt-5" id="choosePaymentSection">
                <div class="row d-flex justify-content-center align-items-center" style="height: 100px;">
                    <div class=" col-4 title-strip-payment text-center">
                        Choose Payment
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-4 p-4">
                        <div class="number-box-payment" id="usingPaymentGate">
                            <img class="payment-icon" src="{{ asset('assets/images/payments/qr.png') }}">
                        </div>
                    </div>
                    <div class="col-4 p-4">
                        <div class="number-box-payment" id="usingCash">
                            <img class="payment-icon" src="{{ asset('assets/images/payments/money.png') }}">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

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
        $('#chooseStripSection').hide();
        $('#choosePaymentSection').show();
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
    }

    function showThanks() {
        Swal.fire({
            title: "Thank you!",
            html: "Please wait while we open the photo app...",
            width: 600,
            padding: "3em",
            color: "#3263d3",
            background: "#fff url({{ asset('assets/images/payments/trees.png') }})",
            timer: 5000, // 20 seconds (in milliseconds)
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
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
                console.log("Swal closed after 20 seconds");
                window.location.reload();
            }
        });
    }

    function constructTransactionObject() {
        let qty = parseInt($('#qtyInput').val());
        let hargaSatuan = parseInt("{{ $product->price }}");
        let productName = "{{ $product->name }}";
        let totalHarga = qty *  hargaSatuan;
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
            confirmButtonText: 'Yes, Process!',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
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
            allowOutsideClick: () => !Swal.isLoading()
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
                                    confirmButtonColor: '#17a2b8'
                                });
                            },
                            onError: function(result) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Payment Failed',
                                    text: 'Payment failed. Please try again.',
                                    confirmButtonColor: '#dc3545'
                                });
                            },
                            onClose: function() {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Payment Cancelled',
                                    text: 'You closed the payment popup. Please try again if you want to complete the payment.',
                                    confirmButtonColor: '#ffc107'
                                });
                            }
                        });
                    } else { // Debug log
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Error',
                            text: 'Payment gateway token not found. Please try again or contact support.',
                            confirmButtonColor: '#dc3545'
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
                        confirmButtonText: 'Continue'
                    }).then(() => {
                        // resetForm();

                        // hitUrl(
                        //     'http://localhost:3020/open-app?app=dslrbooth'
                        // );

                        showThanks()

                    });
                } else {
                    // Fallback for other cases
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Order processed successfully!',
                        confirmButtonColor: '#28a745'
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
                            <p><strong>Amount:</strong> Rp ${new Intl.NumberFormat('id-ID').format(paymentResult.gross_amount)}</p>
                            <p><strong>Payment Method:</strong> ${paymentResult.payment_type.toUpperCase()}</p>
                            <p><strong>Status:</strong> <span class="text-success">PAID</span></p>
                        </div>
                    `,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Continue'
                }).then(() => {
                    // resetForm();

                    // hitUrl(
                    //     'http://localhost:3020/open-app?app=dslrbooth'
                    // );

                    showThanks()


                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Payment Completed',
                    text: 'Payment was successful but there was an issue updating the order status. Please contact support.',
                    confirmButtonColor: '#ffc107'
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
                confirmButtonColor: '#ffc107'
            });
        });
    }

    function handleCash(transactionObj) {
        Swal.fire({
            title: 'Enter Code',
            input: 'text',
            inputPlaceholder: 'e.g. COD3',
            showCancelButton: true,
            confirmButtonText: 'Apply',
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

    $(document).ready(function() {

        // Initially hide the payment section
        $('#choosePaymentSection').hide();

        // Attach the function to the click event
        $('#nextSection').on('click', goToPaymentSection);

        // Button click events
        $('#qtyControlPlus').on('click', function () {
            updateQuantity(1);
        });

        $('#qtyControlMinus').on('click', function () {
            updateQuantity(-1);
        });

        // Initial state (in case default is at limit)
        updateQuantity(0);

        handlePayment();

    });


</script>


</body>
</html>
