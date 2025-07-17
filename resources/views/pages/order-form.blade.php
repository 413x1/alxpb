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

        .voucher-section {
            background-color: #3a3a3a;
            border: 1px solid #555;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
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
            padding: 15px;
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
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
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

        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Midtrans Snap Modal Fix */
        .snap-midtrans {
            z-index: 10000 !important;
        }

        /* Ensure Midtrans iframe has proper styling */
        #snap-midtrans iframe {
            background: white !important;
        }

        /* Fix for Midtrans overlay */
        .snap-midtrans .snap-overlay {
            background: rgba(0, 0, 0, 0.6) !important;
        }

        /* Ensure modal content has white background */
        .snap-midtrans .snap-modal {
            background: white !important;
        }
    </style>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Midtrans Snap CSS -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-6 p-2">
                <div id="carouselExample" class="col-10 carousel slide">
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
            <div class="col-4 p-2 ml-3">
                <form id="orderForm">
                    @csrf
                    <h3>Masukan data diri</h3>
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
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Nama :</label>
                        <input type="text" class="form-control form-control-lg" id="customerName" name="customer_name" placeholder="masukan nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Qty :</label>
                        <input type="number" class="form-control form-control-lg" min="1" value="1" id="qty" name="qty" placeholder="masukan jumlah" required>
                    </div>

                    <!-- Voucher Section -->
                    <div class="voucher-section">
                        <label for="voucherCode" class="form-label">Kode Voucher (Opsional):</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="voucherCode" name="voucher_code" placeholder="Masukan kode voucher">
                            <button type="button" class="btn btn-outline-primary" id="checkVoucherBtn">
                                <span id="checkVoucherText">Cek</span>
                                <span id="checkVoucherSpinner" class="spinner-border spinner-border-sm d-none" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                            </button>
                        </div>
                        <div id="voucherMessage" class="mt-2"></div>

                        <!-- Hidden fields for voucher data -->
                        <input type="hidden" id="voucherCode" value="">
                        <input type="hidden" id="voucherPrice" value="0">
                        <input type="hidden" id="voucherDiscount" value="0">
                        <input type="hidden" id="voucherFinalPrice" value="0">
                        <input type="hidden" id="isVoucherValid" value="false">
                    </div>

                    <!-- Total Section -->
                    <div class="total-section">
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
                        <div class="d-grid gap-3 col-6 mx-auto">
                            <button type="button" id="processBtn" class="btn btn-success btn-lg">Proses</button>
                        </div>
                    </div>
                </form>
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

        // Global function declarations
        function resetVoucher() {
            $('#voucherCode').val('');
            $('#voucherPrice').val('0');
            $('#voucherDiscount').val('0');
            $('#voucherFinalPrice').val('0');
            $('#isVoucherValid').val('false');
            updateTotals();
        }

        function updateTotals() {
            const qty = parseInt($('#qty').val()) || 1;
            const subtotal = basePrice * qty;
            const isVoucherValid = $('#isVoucherValid').val() === 'true';
            const voucherDiscount = parseFloat($('#voucherDiscount').val()) || 0;
            const voucherFinalPrice = parseFloat($('#voucherFinalPrice').val()) || 0;

            let discount = 0;
            let total = subtotal;

            if (isVoucherValid && voucherDiscount > 0) {
                // Use the discount and final price from backend
                discount = voucherDiscount;
                total = voucherFinalPrice;
            }

            // Update display
            $('#subtotalDisplay').text('Rp ' + new Intl.NumberFormat('id-ID').format(subtotal));
            $('#discountDisplay').text('- Rp ' + new Intl.NumberFormat('id-ID').format(discount));
            $('#totalDisplay').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));

            // Show/hide discount row
            if (discount > 0) {
                $('#discountRow').show();
            } else {
                $('#discountRow').hide();
            }

            return { subtotal, discount, total };
        }

        // Function to update order status after successful payment
        function updateOrderStatus(paymentResult, orderData) {
            // Show loading
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

            // Prepare data for status update
            const statusUpdateData = {
                order_id: paymentResult.order_id,
                gross_amount: paymentResult.gross_amount,
                payment_gateway_response: JSON.stringify(paymentResult),
                _token: $('input[name="_token"]').val()
            };

            // Call your update status endpoint
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
                        // Reset form completely
                        $('#orderForm')[0].reset();
                        $('#qty').val(1);

                        // Reset voucher completely
                        resetVoucher();
                        $('#voucherCode').val(''); // Clear voucher input
                        $('#voucherMessage').html(''); // Clear voucher message

                        // Reset totals to original price
                        updateTotals();

                        // Optional: Redirect to success page or order details
                        // window.location.href = '/orders/' + updateResponse.order.id;
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

        $(document).ready(function() {
            basePrice = parseFloat($('#productPriceValue').val());

            // Function to calculate and update totals
            // (This function is now declared globally above)

            // Update totals when quantity changes
            $('#qty').on('input', function() {
                // Auto-remove voucher when quantity changes but keep the code
                if ($('#isVoucherValid').val() === 'true') {
                    // Reset voucher validation but keep the code in input
                    $('#voucherPrice').val('0');
                    $('#voucherDiscount').val('0');
                    $('#voucherFinalPrice').val('0');
                    $('#isVoucherValid').val('false');

                    $('#voucherMessage').html(`
                        <div class="voucher-info">
                            <i class="fas fa-info-circle"></i> Quantity changed. Please click "Cek" to reapply voucher.
                        </div>
                    `);
                }
                updateTotals();
            });

            // Check voucher code
            $('#checkVoucherBtn').click(function() {
                const voucherCode = $('#voucherCode').val().trim();
                const qty = parseInt($('#qty').val()) || 1;

                if (!voucherCode) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: 'Please enter voucher code first!'
                    });
                    return;
                }

                // Show loading state
                $('#checkVoucherBtn').prop('disabled', true);
                $('#checkVoucherText').addClass('d-none');
                $('#checkVoucherSpinner').removeClass('d-none');
                $('#voucherMessage').html('');

                // Ajax request to check voucher
                $.ajax({
                    url: '{{ route("order.check-voucher") }}', // Replace with your actual route
                    method: 'POST',
                    data: {
                        voucher_code: voucherCode,
                        qty: qty,
                        price: basePrice,
                        _token: $('input[name="_token"]').val()
                    },
                    dataType: 'json'
                }).done(function(response) {
                    if (response.success) {
                        // Valid voucher - store values from backend
                        $('#voucherCode').val(voucherCode);
                        $('#voucherPrice').val(response.data.price);
                        $('#voucherDiscount').val(response.data.discount);
                        $('#voucherFinalPrice').val(response.data.final_price);
                        $('#isVoucherValid').val('true');

                        $('#voucherMessage').html(`
                            <div class="voucher-success">
                                <i class="fas fa-check-circle"></i> ${response.message}
                                <br><small>Discount: Rp ${new Intl.NumberFormat('id-ID').format(response.data.discount)} (50% Off)</small>
                            </div>
                        `);

                        updateTotals();
                    } else {
                        // Invalid voucher
                        resetVoucher();
                        $('#voucherMessage').html(`
                            <div class="voucher-error">
                                <i class="fas fa-times-circle"></i> ${response.message}
                            </div>
                        `);
                    }
                }).fail(function(xhr) {
                    resetVoucher();
                    let errorMessage = 'An error occurred while checking voucher.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    $('#voucherMessage').html(`
                        <div class="voucher-error">
                            <i class="fas fa-times-circle"></i> ${errorMessage}
                        </div>
                    `);
                }).always(function() {
                    // Hide loading state
                    $('#checkVoucherBtn').prop('disabled', false);
                    $('#checkVoucherText').removeClass('d-none');
                    $('#checkVoucherSpinner').addClass('d-none');
                });
            });

            // Reset voucher data
            // (This function is now declared globally above)

            // Clear voucher when input changes
            $('#voucherCode').on('input', function() {
                if ($(this).val().trim() === '') {
                    resetVoucher();
                    $('#voucherMessage').html('');
                } else {
                    // Reset validation when user types new code (but don't clear the input)
                    if ($('#isVoucherValid').val() === 'true') {
                        $('#voucherPrice').val('0');
                        $('#voucherDiscount').val('0');
                        $('#voucherFinalPrice').val('0');
                        $('#isVoucherValid').val('false');
                        $('#voucherMessage').html('');
                        updateTotals();
                    }
                }
            });

            // Process order
            $('#processBtn').click(function() {
                // Get form data
                const customerName = $('#customerName').val().trim();
                const qty = $('#qty').val();
                const productId = $('#productId').val();
                const totals = updateTotals();

                // Validate form data
                if (!customerName) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: 'Name is required!'
                    });
                    return;
                }

                if (!qty || qty < 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: 'Quantity must be at least 1!'
                    });
                    return;
                }

                // Prepare confirmation content
                let confirmationHtml = `
                    <div class="text-start">
                        <p><strong>Nama:</strong> ${customerName}</p>
                        <p><strong>Produk:</strong> {{ $product->name }}</p>
                        <p><strong>Quantity:</strong> ${qty}</p>
                        <p><strong>Harga Satuan:</strong> Rp ${new Intl.NumberFormat('id-ID').format(basePrice)}</p>
                        <p><strong>Subtotal:</strong> Rp ${new Intl.NumberFormat('id-ID').format(totals.subtotal)}</p>
                `;

                if (totals.discount > 0) {
                    confirmationHtml += `<p><strong>Discount:</strong> <span class="text-success">- Rp ${new Intl.NumberFormat('id-ID').format(totals.discount)}</span></p>`;
                }

                confirmationHtml += `
                        <p><strong>Total:</strong> <span class="text-primary">Rp ${new Intl.NumberFormat('id-ID').format(totals.total)}</span></p>
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
                            subtotal: totals.subtotal,
                            discount: totals.discount,
                            total_price: totals.total,
                            voucher_code: $('#voucherCode').val().trim() || null,
                            _token: $('input[name="_token"]').val()
                        };

                        // Ajax request
                        return $.ajax({
                            url: '{{ route("order.store") }}', // Replace with your actual route
                            method: 'POST',
                            data: formData,
                            dataType: 'json'
                        }).done(function(response) {
                            return response;
                        }).fail(function(xhr) {
                            let errorMessage = 'An error occurred while processing the order.';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = errors.join('<br>');
                            }

                            Swal.showValidationMessage(errorMessage);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        const response = result.value;

                        if (response.order.snap_token) {
                            snap.pay(response.order.snap_token, {
                                onSuccess: function(result) {
                                    // Update order status in database
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
                        } else {
                            // Fallback for orders without payment gateway
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message || 'Order processed successfully!',
                                confirmButtonColor: '#28a745'
                            }).then(() => {
                                // Reset form completely
                                $('#orderForm')[0].reset();
                                $('#qty').val(1);

                                // Reset voucher completely
                                resetVoucher();
                                $('#voucherCode').val(''); // Clear voucher input
                                $('#voucherMessage').html(''); // Clear voucher message

                                // Reset totals to original price
                                updateTotals();

                                // Optional: Redirect to order detail or list
                                if (response.redirect_url) {
                                    window.location.href = response.redirect_url;
                                }
                            });
                        }
                    }
                });
            });

            // Initialize totals on page load
            updateTotals();
        });
    </script>
@endsection
