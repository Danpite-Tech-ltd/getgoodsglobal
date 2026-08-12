@extends('backEnd.layouts.master')
@section('title', 'Dashboard')
@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('public/backEnd/') }}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/backEnd/') }}/assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet"
        type="text/css" />
    <style>
        .card-bg {
            background: #f3f3f3;
        }
    </style>

@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        {{-- <div class="card">
            <div class="card-header">
                <h3>Accounts Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                            <i class="fe-dollar-sign font-22 avatar-title text-warning"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $account_balance }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Account Balance

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                </div>

                <form action="{{ route('deposit.filtering') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-sm-5"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" value="{{ request()->get('start_date') }}" class="form-control flatdate"
                                    name="start_date">
                            </div>
                        </div>
                        <!--col-sm-3-->
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" value="{{ request()->get('end_date') }}" class="form-control flatdate"
                                    name="end_date">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-credit-card font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $courier_payment }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Courier Payment

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                            <i class="fe-tag font-22 avatar-title text-success"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $officesale_payment }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Office Payment

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                            <i class="fe-pocket font-22 avatar-title text-warning"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $expense_others }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Others Payment

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                            <i class="fe-dollar-sign font-22 avatar-title text-info"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $total_payment }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Total Payment

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                </div>
                <!-- end row-->

                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                            <i class="fe-pocket font-22 avatar-title text-warning"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $boost_cost }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Boost Cost

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $office_cost }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Office Cost

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                            <i class="fe-credit-card font-22 avatar-title text-info"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $bank_deposit }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Bank Deposit

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                            <i class="fe-credit-card font-22 avatar-title text-info"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $packaging_cost }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Packaging Cost

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                            <i class="fe-credit-card font-22 avatar-title text-info"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $transport_cost }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Transport Cost

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                            <i class="fe-credit-card font-22 avatar-title text-info"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $others_expense_cost }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Others

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-dollar-sign font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">৳<span
                                                    data-plugin="counterup">{{ $total_cost }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1 text-truncate">Total Cost

                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                </div>
                <!-- end row-->
            </div>
        </div> --}}

        <div class="card">
            <div class="card-header">
                <h3>Orders Information</h3>
            </div>
            <div class="card-body">
                <div class="row">

                <!-- date filter -->
                <form action="" method="GET">
                        <div class="row mb-3">
                            <div class="col-sm-5"></div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="orderstart_date" class="form-label">Start Date</label>
                                    <input type="date" value="{{ request()->get('orderstart_date') }}"
                                        class="form-control flatdate" name="orderstart_date">
                                </div>
                            </div>
                            <!--col-sm-3-->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="orderend_date" class="form-label">End Date</label>
                                    <input type="date" value="{{ request()->get('orderend_date') }}"
                                        class="form-control flatdate" name="orderend_date">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <br>
                                    <button type="submit" class="btn btn-primary mt-1">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $total_order }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Total Orders</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                            <i class="fe-shopping-bag font-22 avatar-title text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $pending_order }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Pending Orders</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                            <i class="fe-database font-22 avatar-title text-info"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $partial_paid_order }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">In Review Orders</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                            <i class="fe-user font-22 avatar-title text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $confirm_order }}</span></h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Confirmed Orders</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $total_cancel_order }}</span></h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Cancelled Orders</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-info"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $prossecing_order }}</span></h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Processing Orders</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-secondary border-secondary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-secondary"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $shipped_courier }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Dispatched Orders</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-secondary border-secondary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-secondary"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $Delivered_order }}</span>
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Delivered Orders</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $return_order }}</span></h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Returned</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $rts }}</span></h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">NO-RTS</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $refund_initiated }}</span></h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Refund Initiated</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $refund_order }}</span></h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Refund Orders</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ number_format($cancel_rate, 2) }}</span></h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Cancel Rate %</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ number_format($return_rate, 2) }}</span></h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Return Rate %</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                </div>
                <!-- end row-->
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Stock Management</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $total_product }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Total Products
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $total_sku }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Total SKUs
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $available_sku }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Total Available SKUs
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $stock_out_sku }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Out of Stock SKUs
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $low_sku }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Low Stock SKUs
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $total_inventory }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Total Inventory Value (SP)
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                </div>
                <!-- end row-->
            </div>
        </div>

        <!-- Customer Insights -->
        <div class="card">
            <div class="card-header">
                <h3>Customer Insights</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <form action="" method="GET">
                        <div class="row mb-3">
                            <div class="col-sm-5"></div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="customer_start_date" class="form-label">Start Date</label>
                                    <input type="date" value="{{ request()->get('customer_start_date') }}"
                                        class="form-control flatdate" name="customer_start_date">
                                </div>
                            </div>
                            <!--col-sm-3-->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="customer_end_date" class="form-label">End Date</label>
                                    <input type="date" value="{{ request()->get('customer_end_date') }}"
                                        class="form-control flatdate" name="customer_end_date">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <br>
                                    <button type="submit" class="btn btn-primary mt-1">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $total_customer }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Customers
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $total_visitor }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Websites Visitor Count
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                </div>
                <!-- end row-->
            </div>
        </div>


        <!-- Sales Overview -->
        <div class="card">
            <div class="card-header">
                <h3>Sales Overview</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <form action="" method="GET">
                        <div class="row mb-3">
                            <div class="col-sm-5"></div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="sales_start_date" class="form-label">Start Date</label>
                                    <input type="date" value="{{ request()->get('sales_start_date') }}"
                                        class="form-control flatdate" name="sales_start_date">
                                </div>
                            </div>
                            <!--col-sm-3-->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="sales_end_date" class="form-label">End Date</label>
                                    <input type="date" value="{{ request()->get('sales_end_date') }}"
                                        class="form-control flatdate" name="sales_end_date">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <br>
                                    <button type="submit" class="btn btn-primary mt-1">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="col-md-6 col-xl-3">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $total_sale }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Total Sales
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                    <div class="col-md-6 col-xl-4">
                        <div class="widget-rounded-circle card card-bg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ number_format($aov, 2) }}</span>
                                               
                                            </h3>
                                            <p class="text-dark fw-bold mb-1" style="font-size:12px;">Average Order Value (AOV)
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </div> <!-- end col-->
                </div>
                <!-- end row-->
            </div>
        </div>


        <!-- Display None -->
        <div class="row d-none">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>

                        <h4 class="header-title mb-3">Latest 5 Orders</h4>

                        <div class="table-responsive">
                            <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                                <thead class="table-light">
                                    <tr>
                                        <th colspan="2">Id</th>
                                        <th>Invoice</th>
                                        <th>Amount</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latest_order as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="width: 36px;">
                                                <img src="{{ asset($order->product ? $order->product->image->image : '') }}"
                                                    alt="contact-img" title="contact-img" class="rounded-circle avatar-sm" />
                                            </td>

                                            <td>
                                                {{ $order->invoice_id }}
                                            </td>

                                            <td>
                                                {{ $order->amount }}
                                            </td>

                                            <td>
                                                {{ $order->customer ? $order->customer->name : '' }}
                                            </td>
                                            <td>
                                                @php
                                                    $order_status = App\Models\OrderStatus::find($order->order_status);
                                                @endphp
                                                {{ $order_status->name }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>

                        <h4 class="header-title mb-3">Latest Customers</h4>

                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="table-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latest_customer as $customer)
                                        <tr>
                                            <td>
                                                <h5 class="m-0 fw-normal">{{ $loop->iteration }}</h5>
                                            </td>

                                            <td>
                                                {{ $customer->name }}
                                            </td>

                                            <td>
                                                {{ $customer->phone }}
                                            </td>

                                            <td>
                                                {{ $customer->created_at->format('d-m-Y') }}
                                            </td>

                                            <td>
                                                {{ $customer->status }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end .table-responsive-->
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->

        </div>
        <!-- end row -->

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="header-title mb-0">Sales Trend</h4>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary filter-btn active" data-type="daily">Last 7 Days</button>
                        <button class="btn btn-sm btn-outline-primary filter-btn" data-type="weekly">Last 4 Weeks</button>
                        <button class="btn btn-sm btn-outline-primary filter-btn" data-type="monthly">Last 4 Months</button>
                        <button class="btn btn-sm btn-outline-primary filter-btn" data-type="yearly">Last 3 Years</button>
                    </div>
                </div>

                <!-- Summary badges -->
                <div class="mb-3" id="chart-summary">
                    <span class="badge bg-primary me-2 fs-6">💰 Total Amount: <span id="summary-amount">—</span></span>
                    <span class="badge bg-success fs-6">📦 Total Qty: <span id="summary-qty">—</span></span>
                </div>

                <div id="sales-chart"></div>
            </div>
        </div>


    </div> <!-- container -->
@endsection
@section('script')
    <!-- Plugins js-->
    <script src="{{ asset('public/backEnd/') }}/assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/libs/selectize/js/standalone/selectize.min.js"></script>

    <script>
        var chart;

        function loadChart(type = 'daily') {
            $.ajax({
                url: "{{ route('sales.chart') }}",
                type: "GET",
                data: { type: type },
                success: function(res) {
                    let labels       = res.map(item => item.label);
                    let amountData   = res.map(item => parseFloat(item.total_amount));
                    let qtyData      = res.map(item => parseInt(item.total_qty));

                    // Summary badges update
                    let totalAmount = amountData.reduce((a, b) => a + b, 0).toLocaleString('en-US', {minimumFractionDigits: 2});
                    let totalQty    = qtyData.reduce((a, b) => a + b, 0).toLocaleString();
                    $('#summary-amount').text('৳' + totalAmount);
                    $('#summary-qty').text(totalQty + ' pcs');
                    // alert(qtyData)
                    let options = {
                        chart: {
                            height: 370,
                            type: "line",
                            toolbar: { show: false },
                            animations: { enabled: true, speed: 600 }
                        },
                        series: [
                            {
                                name: "Amount (৳)",
                                data: amountData
                            },
                            {
                                name: "Qty (pcs)",
                                data: qtyData
                            }
                        ],
                        colors: ['#4e73df', '#1cc88a'],
                        stroke: {
                            curve: 'smooth',
                            width: [3, 3]
                        },
                        markers: {
                            size: 4,
                            hover: { size: 6 }
                        },
                        xaxis: {
                            categories: labels,
                            labels: {
                                style: { fontSize: '12px' }
                            }
                        },
                        yaxis: [
                            {
                                title: { text: "Amount (৳)" },
                                labels: {
                                    formatter: val => '৳' + val.toLocaleString()
                                }
                            },
                            {
                                opposite: true,
                                title: { text: "Qty (pcs)" },
                                labels: {
                                    formatter: val => val + ' pcs'
                                }
                            }
                        ],
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: [
                                { formatter: val => '৳' + val.toLocaleString('en-US', {minimumFractionDigits: 2}) },
                                { formatter: val => val + ' pcs' }
                            ]
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right'
                        },
                        grid: {
                            borderColor: '#f1f1f1'
                        }
                    };

                    if (chart) {
                        chart.destroy();
                    }

                    chart = new ApexCharts(document.querySelector("#sales-chart"), options);
                    chart.render();
                }
            });
        }

        // Default load
        loadChart('daily');

        // Button click with active class toggle
        $(document).on('click', '.filter-btn', function () {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            let type = $(this).data('type');
            loadChart(type);
        });
    </script>

@endsection
