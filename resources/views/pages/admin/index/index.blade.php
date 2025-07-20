@extends('layouts.admin.app')

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <!-- Order Metrics Row -->
        <div class="row mb-4">
            <!-- Total Orders Card -->
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card bg-primary text-white metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-75 mb-1">Total Orders</h6>
                                <h3 class="text-white mb-0">{{ number_format($totalOrder) }}</h3>
                            </div>
                            <div class="text-white">
                                <i class="fa-solid fa-shopping-cart fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Orders Card -->
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card bg-warning text-white metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-75 mb-1">Pending Orders</h6>
                                <h3 class="text-white mb-0">{{ number_format($pendingOrder) }}</h3>
                            </div>
                            <div class="text-white">
                                <i class="fa-solid fa-clock fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Orders Card -->
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card bg-success text-white metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-75 mb-1">Completed Orders</h6>
                                <h3 class="text-white mb-0">{{ number_format($completedOrder) }}</h3>
                            </div>
                            <div class="text-white">
                                <i class="fa-solid fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Card -->
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card bg-info text-white metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-75 mb-1">Monthly Revenue</h6>
                                <h3 class="text-white mb-0">Rp {{ Number::format($completedAmount) }}</h3>
                            </div>
                            <div class="text-white">
                                <i class="fa-solid fa-wallet fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device Orders Row -->
        <div class="row mb-4">
            @foreach($devices as $device)
                <div class="col-lg-3 col-sm-6 mb-3">
                    <div class="card bg-light border metric-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">{{ $device->name }}</h6>
                                    <h3 class="text-dark mb-0">{{ number_format($device->orders_count) }}</h3>
                                    <small class="text-muted">Orders</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Voucher Metrics Row -->
        <div class="row mb-4">
            <!-- Total Vouchers Card -->
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="card bg-secondary text-white metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-75 mb-1">Total Vouchers</h6>
                                <h3 class="text-white mb-0">{{ number_format($voucher) }}</h3>
                            </div>
                            <div class="text-white">
                                <i class="fa-solid fa-ticket fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Redeemed Vouchers Card -->
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="card bg-danger text-white metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-75 mb-1">Redeemed Vouchers</h6>
                                <h3 class="text-white mb-0">{{ number_format($redeemedVoucher) }}</h3>
                            </div>
                            <div class="text-white">
                                <i class="fa-solid fa-check fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Vouchers Card -->
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="card bg-dark text-white metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-75 mb-1">Available Vouchers</h6>
                                <h3 class="text-white mb-0">{{ number_format($availableVoucher) }}</h3>
                            </div>
                            <div class="text-white">
                                <i class="fa-solid fa-gift fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Metrics Row -->
        <div class="row mb-4">
            <!-- Active Devices Card -->
            <div class="col-lg-6 col-sm-6 mb-3">
                <div class="card bg-success text-white metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-75 mb-1">Active Devices</h6>
                                <h3 class="text-white mb-0">{{ number_format($deviceCount) }}</h3>
                                <small class="text-white-75">Photo booth units</small>
                            </div>
                            <div class="text-white">
                                <i class="fa-solid fa-camera fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Users Card -->
            <div class="col-lg-6 col-sm-6 mb-3">
                <div class="card bg-primary text-white metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-75 mb-1">Total Users</h6>
                                <h3 class="text-white mb-0">{{ number_format($user) }}</h3>
                                <small class="text-white-75">Registered customers</small>
                            </div>
                            <div class="text-white">
                                <i class="fa-solid fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('after-css')
    <style>
        /* Modern card backgrounds - soft & pleasant colors */
        .bg-primary {
            background-color: #4285f4 !important;
            box-shadow: 0 2px 12px rgba(66, 133, 244, 0.15);
        }

        .bg-secondary {
            background-color: #5f6368 !important;
            box-shadow: 0 2px 12px rgba(95, 99, 104, 0.15);
        }

        .bg-success {
            background-color: #34a853 !important;
            box-shadow: 0 2px 12px rgba(52, 168, 83, 0.15);
        }

        .bg-info {
            background-color: #17a2b8 !important;
            box-shadow: 0 2px 12px rgba(23, 162, 184, 0.15);
        }

        /* Softer text colors for better readability */
        .text-success {
            color: #28a745 !important;
        }

        .text-info {
            color: #17a2b8 !important;
        }

        .text-warning {
            color: #fd7e14 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        /* Pleasant badge colors */
        .badge.bg-success {
            background-color: #28a745 !important;
            color: white !important;
        }

        .badge.bg-info {
            background-color: #17a2b8 !important;
            color: white !important;
        }

        .badge.bg-warning {
            background-color: #fd7e14 !important;
            color: white !important;
        }

        .badge.bg-primary {
            background-color: #007bff !important;
            color: white !important;
        }

        /* Softer progress bar colors */
        .progress-bar.bg-success {
            background-color: #28a745 !important;
        }

        .progress-bar.bg-warning {
            background-color: #fd7e14 !important;
        }

        /* Subtle background colors */
        .bg-success-subtle {
            background-color: rgba(40, 167, 69, 0.08) !important;
            color: #28a745 !important;
        }

        .bg-info-subtle {
            background-color: rgba(23, 162, 184, 0.08) !important;
            color: #17a2b8 !important;
        }

        .bg-warning-subtle {
            background-color: rgba(253, 126, 20, 0.08) !important;
            color: #fd7e14 !important;
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.08) !important;
            color: #dc3545 !important;
        }

        /* Enhanced metric cards - rounded corners back */
        .metric-card {
            transition: all 0.3s ease;
            border-radius: 15px !important;
            border: none;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        /* Status items styling - rounded */
        .status-item {
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .status-item:hover {
            background-color: rgba(0,0,0,0.02);
            border-radius: 10px;
        }

        .status-icon {
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .status-item:hover .status-icon {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Enhanced styling - rounded back */
        .border-end {
            border-right: 2px solid #dee2e6 !important;
        }

        .progress {
            border-radius: 15px !important;
            overflow: hidden;
        }

        .card {
            border-radius: 15px !important;
            border: none;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
            border-bottom: 1px solid #e9ecef;
        }

        .shadow-sm {
            box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
        }

        /* Status indicator styling - rounded */
        .status-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50% !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Status indicator with softer colors */
        .status-indicator.bg-success {
            background-color: #28a745 !important;
        }

        .status-indicator.bg-info {
            background-color: #17a2b8 !important;
        }

        .status-indicator.bg-warning {
            background-color: #fd7e14 !important;
        }

        /* Remove duplicate subtle backgrounds */

        /* Text opacity classes */
        .text-white-50 {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .text-white-75 {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        /* Button enhancements - rounded back */
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 10px !important;
            transition: all 0.3s ease;
        }

        .btn-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Badge styling - rounded back */
        .badge {
            border-radius: 0.375rem !important;
        }

        /* Subtle backgrounds - rounded back */
        .bg-success-subtle,
        .bg-info-subtle,
        .bg-warning-subtle,
        .bg-danger-subtle {
            border-radius: 10px !important;
        }

        /* Animation for progress bars */
        @keyframes progressAnimation {
            0% { width: 0%; }
            100% { width: var(--progress-width); }
        }

        .progress-bar-animated {
            animation: progressAnimation 2s ease-in-out;
        }
    </style>
@endsection
