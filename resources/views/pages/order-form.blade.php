@extends('layouts.app')

@section('css')
    <style>
        .ratio-4x6 {
            --bs-aspect-ratio: calc(6 / 4 * 100%);
        }

        /* Dark theme styling */
        body {
            background-color: #2c2c2c;
            color: #ffffff;
        }

        .container {
            background-color: #2c2c2c;
        }

        .form-label {
            color: #ffffff;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control, .form-control:focus {
            background-color: #4a4a4a;
            border: 1px solid #555;
            color: #ffffff;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control:disabled {
            background-color: #3a3a3a;
            color: #cccccc;
        }

        /* Payment Method Selection */
        .payment-methods {
            background-color: #3a3a3a;
            border: 1px solid #555;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .payment-option {
            background-color: #4a4a4a;
            border: 2px solid #555;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .payment-option:hover {
            border-color: #007bff;
            background-color: #525252;
        }

        .payment-option.active {
            border-color: #28a745;
            background-color: #2d5a3d;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
        }

        .payment-option .check-icon {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #28a745;
            font-size: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .payment-option.active .check-icon {
            opacity: 1;
        }

        .payment-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .qris-icon {
            color: #17a2b8;
        }

        .voucher-icon {
            color: #ffc107;
        }

        .payment-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .payment-description {
            font-size: 14px;
            color: #cccccc;
            margin: 0;
        }

        /* Voucher Section */
        .voucher-section {
            background-color: #3a3a3a;
            border: 1px solid #555;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            display: none;
        }

        .voucher-section.show {
            display: block;
            animation: fadeInDown 0.3s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .voucher-section .form-label {
            color: #cccccc;
            font-size: 14px;
        }

        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
            background-color: transparent;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            border-color: #007bff;
            color: #ffffff;
        }

        .voucher-discount {
            color: #28a745;
            font-weight: bold;
        }

        .voucher-error {
            color: #dc3545;
            font-size: 0.875rem;
        }

        .voucher-success {
            color: #28a745;
            font-size: 0.875rem;
        }

        .voucher-info {
            color: #17a2b8;
            font-size: 0.875rem;
        }

        .total-section {
            background-color: #3a3a3a;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid #555;
        }

        .total-section .d-flex span {
            color: #ffffff;
        }

        .total-section strong {
            color: #ffffff;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 8px;
            font-weight: 500;
            padding: 12px 24px;
            font-size: 16px;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-success:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
        }

        h3 {
            color: #ffffff;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .carousel {
            border-radius: 12px;
            overflow: hidden;
        }

        .img-thumbnail {
            border: none;
            border-radius: 12px;
        }

        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .voucher-section .text-danger {
            color: #dc3545 !important;
        }

        /* Payment method indicator */
        .payment-indicator {
            background-color: #495057;
            color: #ffffff;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 10px;
        }

        .payment-indicator.qris {
            background-color: #17a2b8;
        }

        .payment-indicator.voucher {
            background-color: #ffc107;
            color: #212529;
        }

        /* Midtrans Snap Modal Fix */
        .snap-midtrans {
            z-index: 10000 !important;
        }

        #snap-midtrans iframe {
            background: white !important;
        }

        .snap-midtrans .snap-overlay {
            background: rgba(0, 0, 0, 0.6) !important;
        }

        .snap-midtrans .snap-modal {
            background: white !important;
        }

        /* Responsive Design */
        .order-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .product-section {
            flex: 0 0 55%;
            max-width: 55%;
        }

        .form-section {
            flex: 0 0 45%;
            max-width: 45%;
            min-width: 450px;
        }

        .carousel-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }

        /* Desktop (Large screens) */
        @media (min-width: 1200px) {
            .form-section {
                min-width: 500px;
            }

            .carousel-wrapper {
                max-width: 700px;
            }
        }

        /* Tablet (Medium screens) */
        @media (max-width: 1199px) and (min-width: 769px) {
            .product-section {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .form-section {
                flex: 0 0 50%;
                max-width: 50%;
                min-width: 400px;
            }

            .carousel-wrapper {
                max-width: 500px;
            }
        }

        /* Mobile (Small screens) */
        @media (max-width: 768px) {
            .order-container .d-flex {
                flex-direction: column !important;
            }

            .product-section,
            .form-section {
                flex: 1 1 100% !important;
                max-width: 100% !important;
                min-width: auto !important;
            }

            .carousel-wrapper {
                max-width: 100%;
                margin-bottom: 20px;
            }

            .form-section {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            .payment-option {
                padding: 12px;
                margin-bottom: 12px;
            }

            .payment-title {
                font-size: 15px;
            }

            .payment-description {
                font-size: 13px;
            }

            .total-section {
                padding: 15px;
            }

            h3 {
                font-size: 1.5rem;
                margin-bottom: 20px;
            }

            .btn-lg {
                padding: 10px 20px;
                font-size: 15px;
            }
        }

        /* Very small mobile screens */
        @media (max-width: 480px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .payment-methods,
            .voucher-section,
            .total-section {
                padding: 15px;
                margin-bottom: 15px;
            }

            .payment-option {
                padding: 10px;
            }

            .form-control-lg {
                padding: 10px 12px;
                font-size: 14px;
            }

            .btn-success {
                padding: 10px 16px;
                font-size: 14px;
            }
        }
    </style>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Midtrans Snap JS -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="order-container">
            <div class="d-flex justify-content-center mb-3">
                <div class="product-section p-3">
                    <div class="carousel-wrapper">
                        <div id="carouselExample" class="carousel slide">
                            <div class="carousel-inner">
                                @if ($product->banners)
                                    @foreach ($product->banners as $banner)
                                        <div class="carousel-item @if($loop->first) active @endif">
                                            <img src="{{ $banner->url }}" class="img-thumbnail d-block w-100" alt="{{ $banner->name }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-section p-3">
                    <form id="orderForm">
                        @csrf
                        <h3>Order Details</h3>

                        <!-- Product Information -->
                        <div class="mb-3">
                            <label for="productName" class="form-label">Tipe :</label>
                            <input type="text" class="form-control form-control-lg" id="productName" value="{{ $product->name }}" disabled>
                            <input type="hidden" id="productId" value="{{ $product->id }}">
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Harga :</label>
                            <input type="text" class="form-control form-control-lg" id="productPrice" value="Rp {{ number_format($product->price, 0, ',', '.') }}" disabled>
                            <input type="hidden" id="productPriceValue" value="{{ $product->price }}">
                        </div>

                        <!-- Customer Information -->
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Nama :</label>
                            <input type="text" class="form-control form-control-lg" id="customerName" name="customer_name" placeholder="Masukan nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="qty" class="form-label">Qty :</label>
                            <input type="number" class="form-control form-control-lg" min="1" value="1" id="qty" name="qty" placeholder="Masukan jumlah" required>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="payment-methods">
                            <h5 class="mb-3" style="color: #ffffff;">Pilih Metode Pembayaran</h5>

                            <div class="payment-option" data-payment="qris">
                                <i class="fas fa-check-circle check-icon"></i>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-qrcode payment-icon qris-icon"></i>
                                    <div>
                                        <div class="payment-title">QRIS Payment</div>
                                        <p class="payment-description">Bayar menggunakan QR Code melalui aplikasi e-wallet</p>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-option" data-payment="voucher">
                                <i class="fas fa-check-circle check-icon"></i>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-ticket-alt payment-icon voucher-icon"></i>
                                    <div>
                                        <div class="payment-title">Voucher Payment</div>
                                        <p class="payment-description">Bayar menggunakan kode voucher yang tersedia</p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="selectedPaymentMethod" value="">
                        </div>

                        <!-- Voucher Section (Hidden by default) -->
                        <div class="voucher-section" id="voucherSection">
                            <label for="voucherCode" class="form-label">Kode Voucher: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="voucherCode" name="voucher_code" placeholder="Masukan kode voucher" required>
                            <div id="voucherMessage" class="mt-2"></div>
                        </div>

                        <!-- Total Section -->
                        <div class="total-section">
                            <div id="paymentMethodIndicator" class="payment-indicator" style="display: none;">
                                <i class="fas fa-info-circle"></i> <span id="paymentMethodText">Pilih metode pembayaran</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotalDisplay">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2" id="discountRow" style="display: none !important;">
                                <span>Diskon:</span>
                                <span id="discountDisplay" class="voucher-discount">- Rp 0</span>
                            </div>
                            <hr class="my-2" style="border-color: #555;">
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong id="totalDisplay">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-grid">
                                <button type="button" id="processBtn" class="btn btn-success btn-lg" disabled>
                                    <i class="fas fa-credit-card me-2"></i>Proses Pembayaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let basePrice = 0;
        let selectedPaymentMethod = '';

        // Global function declarations
        function resetVoucher() {
            $('#voucherCode').val('');
            $('#voucherMessage').html('');
            $('#voucherCode').removeClass('is-valid is-invalid');
            updateTotals();
        }

        function updateTotals() {
            const qty = parseInt($('#qty').val()) || 1;
            const total = basePrice * qty;

            // Update display
            $('#subtotalDisplay').text('Rp ' + new Intl.NumberFormat('id-ID').format(basePrice * qty));
            $('#totalDisplay').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));

            return total;
        }

        function updatePaymentMethodIndicator() {
            const indicator = $('#paymentMethodIndicator');
            const text = $('#paymentMethodText');

            if (selectedPaymentMethod === 'qris') {
                indicator.removeClass('voucher').addClass('qris').show();
                text.html('<i class="fas fa-qrcode me-1"></i>QRIS Payment');
            } else if (selectedPaymentMethod === 'voucher') {
                indicator.removeClass('qris').addClass('voucher').show();
                text.html('<i class="fas fa-ticket-alt me-1"></i>Voucher Payment');
            } else {
                indicator.hide();
            }
        }

        function validateForm() {
            const customerName = $('#customerName').val().trim();
            const qty = $('#qty').val();
            const isVoucherPayment = selectedPaymentMethod === 'voucher';
            const isVoucherValid = $('#voucherCode').val().trim();

            const isFormValid = customerName && qty && selectedPaymentMethod &&
                (!isVoucherPayment || isVoucherValid);

            $('#processBtn').prop('disabled', !isFormValid);

            return isFormValid;
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
                        resetForm();

                        hitUrls(
                            'http://localhost:3020/open-app?app=dslrbooth',
                            'http://localhost:3020/bring-to-front?app=chrome'
                        );

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

        function resetForm() {
            $('#orderForm')[0].reset();
            $('#qty').val(1);
            resetVoucher();
            selectedPaymentMethod = '';
            $('#selectedPaymentMethod').val('');
            $('.payment-option').removeClass('active');
            $('#voucherSection').removeClass('show');
            $('#voucherCode').attr('required', false);
            $('#voucherCode').removeClass('is-invalid is-valid');
            updatePaymentMethodIndicator();
            updateTotals();
            validateForm();
        }

        function hitUrls(firstUrl, secondUrl) {
            // Hit the first URL immediately
            fetch(firstUrl)
                .then(response => {
                    console.log(`First URL responded with status: ${response.status}`);
                })
                .catch(error => {
                    console.error(`Error hitting first URL: ${error}`);
                });

            // Wait 10 minutes (600000 ms), then hit the second URL
            setTimeout(() => {
                fetch(secondUrl)
                    .then(response => {
                        console.log(`Second URL responded with status: ${response.status}`);
                    })
                    .catch(error => {
                        console.error(`Error hitting second URL: ${error}`);
                    });
            }, 15 * 60 * 1000);
            // }, 60 * 1000); // 10 minutes in milliseconds
        }

        $(document).ready(function() {
            basePrice = parseFloat($('#productPriceValue').val());

            // Payment method selection
            $('.payment-option').click(function() {
                $('.payment-option').removeClass('active');
                $(this).addClass('active');

                selectedPaymentMethod = $(this).data('payment');
                $('#selectedPaymentMethod').val(selectedPaymentMethod);

                if (selectedPaymentMethod === 'voucher') {
                    $('#voucherSection').addClass('show');
                    $('#voucherCode').attr('required', true);
                } else {
                    $('#voucherSection').removeClass('show');
                    $('#voucherCode').attr('required', false);
                    resetVoucher();
                }

                updatePaymentMethodIndicator();
                validateForm();
            });

            // Validate form on customer name change
            $('#customerName').on('input keyup', function() {
                validateForm();
            });

            // Update totals when quantity changes
            $('#qty').on('input change', function() {
                updateTotals();
                validateForm();
            });

            // Validate form when voucher code changes
            $('#voucherCode').on('input keyup paste', function() {
                const voucherCode = $(this).val().trim();
                if (selectedPaymentMethod === 'voucher') {
                    if (voucherCode === '') {
                        $(this).addClass('is-invalid').removeClass('is-valid');
                        $('#voucherMessage').html(`
                        <div class="voucher-error">
                            <i class="fas fa-exclamation-circle"></i> Voucher code is required for voucher payment
                        </div>
                    `);
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#voucherMessage').html('');
                    }
                }
                validateForm();
            });

            // Process order - Updated to handle your controller responses
            $('#processBtn').click(function() {
                if (!validateForm()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Form!',
                        text: 'Please fill all required fields and select payment method.',
                        confirmButtonColor: '#ffc107'
                    });
                    return;
                }

                const customerName = $('#customerName').val().trim();
                const qty = $('#qty').val();
                const productId = $('#productId').val();
                const total = updateTotals();

                // Prepare confirmation content
                let confirmationHtml = `
                <div class="text-start">
                    <p><strong>Nama:</strong> ${customerName}</p>
                    <p><strong>Produk:</strong> {{ $product->name }}</p>
                    <p><strong>Quantity:</strong> ${qty}</p>
                    <p><strong>Harga Satuan:</strong> Rp ${new Intl.NumberFormat('id-ID').format(basePrice)}</p>
                    <p><strong>Total:</strong> <span class="text-primary">Rp ${new Intl.NumberFormat('id-ID').format(total)}</span></p>
                    <p><strong>Metode Pembayaran:</strong> <span class="text-primary">${selectedPaymentMethod === 'qris' ? 'QRIS Payment' : 'Voucher Payment'}</span></p>
            `;

                if (selectedPaymentMethod === 'voucher') {
                    confirmationHtml += `<p><strong>Kode Voucher:</strong> <span class="text-warning">${$('#voucherCode').val().trim()}</span></p>`;
                }

                confirmationHtml += `
                </div>
                <br>
                <p>Are you sure you want to process this order?</p>
            `;

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
                        // Prepare data for Ajax request
                        const formData = {
                            product_id: productId,
                            customer_name: customerName,
                            qty: qty,
                            payment_method: selectedPaymentMethod,
                            voucher_code: selectedPaymentMethod === 'voucher' ? $('#voucherCode').val().trim() : null,
                            _token: $('input[name="_token"]').val()
                        };

                        // Ajax request
                        return $.ajax({
                            url: '{{ route("order.store") }}',
                            method: 'POST',
                            data: formData,
                            dataType: 'json'
                        }).done(function(response) {
                            return response;
                        }).fail(function(xhr) {

                            // Handle voucher validation errors (400 status from your controller)
                            if (xhr.status === 400 && xhr.responseJSON) {
                                const errorMessage = xhr.responseJSON.message;

                                // Check if it's a voucher-related error
                                if (selectedPaymentMethod === 'voucher' &&
                                    (errorMessage.toLowerCase().includes('voucher') ||
                                        errorMessage.toLowerCase().includes('code'))) {

                                    // Close current popup and show voucher error popup
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
                                            allowOutsideClick: true,
                                            didClose: () => {
                                                // Focus on voucher input after closing
                                                $('#voucherCode').focus().select();
                                                $('#voucherCode').addClass('is-invalid');
                                                $('#voucherMessage').html(`
                                                <div class="voucher-error">
                                                    <i class="fas fa-exclamation-circle"></i> ${errorMessage}
                                                </div>
                                            `);
                                            }
                                        });
                                    }, 100);

                                    // Prevent the original error handling
                                    return Promise.reject('voucher_handled');
                                }
                            }

                            // Handle other errors (422 validation, 500 server error, etc.)
                            let errorMessage = 'An error occurred while processing the order.';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const allErrors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = allErrors.join('<br>');
                            }

                            Swal.showValidationMessage(errorMessage);
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
                                    <p><strong>Customer:</strong> ${customerName}</p>
                                    <p><strong>Product:</strong> {{ $product->name }}</p>
                                    <p><strong>Quantity:</strong> ${response.order.qty}</p>
                                    <p><strong>Total:</strong> Rp ${new Intl.NumberFormat('id-ID').format(response.order.total_price)}</p>
                                    <p><strong>Payment Method:</strong> <span class="text-warning">Voucher</span></p>
                                    <p><strong>Voucher Code:</strong> <span class="text-success">${$('#voucherCode').val().trim()}</span></p>
                                    <p><strong>Status:</strong> <span class="text-success">PAID</span></p>
                                </div>
                            `,
                                confirmButtonColor: '#28a745',
                                confirmButtonText: 'Continue'
                            }).then(() => {
                                resetForm();

                                hitUrls(
                                    'http://localhost:3020/open-app?app=dslrbooth',
                                    'http://localhost:3020/bring-to-front?app=chrome'
                                );

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
            });

            // Initialize totals on page load
            updateTotals();
            validateForm();
        });
    </script>
@endsection
